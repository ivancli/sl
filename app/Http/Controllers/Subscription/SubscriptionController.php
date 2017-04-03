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
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Exceptions\JsonDecodeException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $request;
    protected $subscriptionRepo;
    protected $productRepo;
    protected $userRepo;

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

    public function show(Subscription $subscription)
    {
        $updatePaymentProfileLink = $this->subscriptionRepo->generateUpdatePaymentProfileLink($subscription);
        $subscriptionPlan = $subscription->apiSubscription->product();
        $transactions = $this->subscriptionRepo->getTransactions($subscription);
        $status = true;
        return compact(['updatePaymentProfileLink', 'subscriptionPlan', 'transactions', 'status']);
    }

    /**
     * Accept subscription update from Chargify
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws JsonDecodeException
     * @throws SubscriptionNotFoundException
     */
    public function create()
    {
        $data = $this->request->all();
        /* TODO validation here */
        if (!isset($data['ref']) || !isset($data['id'])) {
            abort(403);
        }

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
        $userSubscription->token = null;
        $userSubscription->save();

        return redirect()->route('home.get');
    }

    /**
     * User without subscription will come to this page and reactivate/subscribe
     * @param Subscription $subscription
     */
    public function edit(Subscription $subscription)
    {

    }

    /**
     * Subscription Upgrade or Downgrade
     * @param Subscription $subscription
     * @return array
     */
    public function update(Subscription $subscription)
    {
        /**
         * If subscription is not active, that means subscription profile does not actively charging from credit card/payment profile
         * Updating product in subscription will not cause pro-rata issue.
         *
         * However, for trialing subscriptions which do not have credit card/payment details.
         * It is impossible to migrate, in which will cause pro-rata calculation and charge money from users
         *
         */
        $status = true;

        if ($subscription->isActive == true) { //migrate active subscription
            $subscription = $this->subscriptionRepo->migrateSubscription($subscription, $this->request->all());
            if (isset($subscription->errors)) {
                $errors = $subscription->errors;
                $status = false;
            }
        } else { //update subscription which are not active
            $subscription = $this->subscriptionRepo->updateSubscription($subscription, $this->request->all());
            if (isset($subscription->errors)) {
                $errors = $subscription->errors;
                $status = false;
            }
        }
        return compact(['status', 'subscription', 'errors']);
    }
}