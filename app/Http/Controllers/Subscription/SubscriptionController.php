<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/3/2017
 * Time: 5:21 PM
 */

namespace App\Http\Controllers\Subscription;


use App\Contracts\Repositories\Subscription\ProductContract;
use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Contracts\Repositories\User\UserContract;
use App\Exceptions\JsonDecodeException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    var $request;
    var $subscriptionRepo;
    var $productRepo;
    var $userRepo;

    public function __construct(Request $request,
                                SubscriptionContract $subscriptionContract,
                                ProductContract $productContract,
                                UserContract $userContract
    )
    {
        $this->request = $request;
        $this->subscriptionRepo = $subscriptionContract;
        $this->productRepo = $productContract;
        $this->userRepo = $userContract;
    }

    public function store()
    {
        $data = $this->request->all();
        /* TODO validation here */
        if (!isset($data['ref']) || !isset($data['id'])) {
            abort(403);
        }

        $subscriptionPlanId = $this->request->get('subscription_plan_id');
        $subscriptionPlan = $this->productRepo->getProductByProductId($subscriptionPlanId);

        /*TODO get product from chargify*/
        $data = $this->request->all();
        $reference = json_decode($data['ref']);
        if (is_null($reference) && json_last_error() != JSON_ERROR_NONE) {
            throw new JsonDecodeException();
        }

        if (!property_exists($reference, 'user_id') || !property_exists($reference, 'verification_code')) {
            abort(403);
        }

        $user = $this->userRepo->get($reference->user_id);
        $userSubscription = $user->subscription;
        if (is_null($userSubscription)) {
            throw new SubscriptionNotFoundException();
        }

        $token = $reference->verification_code;

        if ($token != $userSubscription->token) {
            abort(403);
        }

        $apiSubscription = $this->subscriptionRepo->get($data['id']);
        $userSubscription->api_subscription_id = $apiSubscription->id;
    }
}