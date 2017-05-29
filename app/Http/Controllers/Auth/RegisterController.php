<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Repositories\Subscription\ProductContract as SubscriptionPlanContract;
use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Models\Subscription;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectToRouteName = 'home.get';
    protected $subscriptionPlanRepo, $subscriptionRepo;

    /**
     * Create a new controller instance.
     *
     * @param SubscriptionPlanContract $subscriptionPlanContract
     * @param SubscriptionContract $subscriptionContract
     */
    public function __construct(
        SubscriptionPlanContract $subscriptionPlanContract,
        SubscriptionContract $subscriptionContract
    )
    {
        $this->middleware('guest');

        $this->subscriptionPlanRepo = $subscriptionPlanContract;
        $this->subscriptionRepo = $subscriptionContract;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'agree_terms' => 'required',
            'subscription_plan_id' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     * @throws SubscriptionNotFoundException
     */
    protected function create(array $data)
    {
        $subscriptionPlan = $this->subscriptionPlanRepo->getProductByProductId($data['subscription_plan_id']);

        $referer = request()->server('HTTP_REFERER');
        $path = urlPath($referer);
        if (!is_null($path)) {
            $subscriptionLocation = strpos($path, 'us') !== false ? 'us' : 'au';
        } else {
            $subscriptionLocation = null;
        }

        $user = User::create([
            'title' => array_get($data, 'title'),
            'first_name' => array_get($data, 'first_name'),
            'last_name' => array_get($data, 'last_name'),
            'email' => array_get($data, 'email'),
            'password' => bcrypt(array_get($data, 'password')),
        ]);
        $user->subscription()->save(new Subscription);
        $couponCode = isset($couponCode) ? $couponCode : '';

        if ($subscriptionPlan->require_credit_card) {
            /* subscription requires credit card, send new user to sign up page*/
            $this->redirectTo = $this->subscriptionPlanRepo->generateSignUpPageLink($subscriptionPlan->id, $user, $couponCode);
        } else {
            /*subscription does not require credit card, create subscription via API*/
            $country = array_get($data, 'country', 'AU');
            $state = array_get($data, 'state');

            $result = $this->subscriptionRepo->createSubscription([
                "product_id" => $subscriptionPlan->id,
                "customer_attributes" => array(
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "country" => $country,
                    "state" => $state
                ),
                "coupon_code" => $couponCode,
                "location" => $subscriptionLocation,
            ]);

            // update api_subscription_id
            $subscription = $user->subscription;
            $subscription->api_subscription_id = $result->id;
            $subscription->location = $subscriptionLocation;
            $subscription->save();
        }
        return $user;
    }

    protected function externalValidator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'agree_terms' => 'required',
            'subscription_plan_id' => 'required'
        ]);
    }

    public function externalRegister(Request $request)
    {
        $validator = $this->externalValidator($request->all());

        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            if ($request->has('callback')) {
                return response()->json(compact(['errors', 'status']))->setCallback($request->get('callback'));
            } else if ($request->wantsJson()) {
                return response()->json(compact(['errors', 'status']));
            } else {
                return compact(['errors', 'status']);
            }
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        $redirect_path = $this->redirectTo;
        if ($request->has('callback')) {
            $redirect_path = redirect($redirect_path)->getTargetUrl();
            return response()->json(compact(['redirect_path']))->withCallback($request->get('callback'));
        } elseif ($request->ajax()) {
            $redirect_path = redirect($redirect_path)->getTargetUrl();
            return new JsonResponse(compact(['redirect_path']));
        } else {
            return redirect($redirect_path);
        }
    }

    protected function registered(Request $request, $user)
    {
        /* TODO redirect admin users to administration page */
        /* TODO redirect normal users to home page which is '/' */
        $redirect_path = $this->redirectTo;
        if ($request->has('callback')) {
            $redirect_path = redirect($redirect_path)->getTargetUrl();
            return response()->json(compact(['redirect_path']))->withCallback($request->get('callback'));
        } elseif ($request->ajax()) {
            $redirect_path = redirect($redirect_path)->getTargetUrl();
            return new JsonResponse(compact(['redirect_path']));
        } else {
            return redirect($redirect_path);
        }
    }
}