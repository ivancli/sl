<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/20/2017
 * Time: 3:41 PM
 */

namespace App\Services\Subscription;


use App\Contracts\Repositories\Subscription\ProductContract;
use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Contracts\Repositories\UserManagement\UserContract;
use App\Exceptions\JsonDecodeException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionService
{
    #region repositories

    protected $subscriptionRepo;
    protected $productRepo;
    protected $userRepo;

    #endregion

    public function __construct(SubscriptionContract $subscriptionContract, ProductContract $productContract, UserContract $userContract)
    {
        #region repositories binding
        $this->subscriptionRepo = $subscriptionContract;
        $this->productRepo = $productContract;
        $this->userRepo = $userContract;
        #endregion
    }

    public function show(Subscription $subscription)
    {
        $updatePaymentProfileLink = $this->subscriptionRepo->generateUpdatePaymentProfileLink($subscription);
        $subscriptionPlan = $subscription->apiSubscription->product();
        $transactions = $this->subscriptionRepo->getTransactions($subscription);
        return compact(['updatePaymentProfileLink', 'subscriptionPlan', 'transactions']);
    }

    public function create(array $data)
    {
        $reference = json_decode(array_get($data, 'ref'));
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
    }

    public function update(Subscription $subscription, array $data)
    {
        if ($subscription->isActive == true) { //migrate active subscription
            $subscription = $this->subscriptionRepo->migrateSubscription($subscription, $data);
            if (isset($subscription->errors)) {
                $errors = $subscription->errors;
                $status = false;
            }
        } else { //update subscription which are not active
            $subscription = $this->subscriptionRepo->updateSubscription($subscription, $data);
            if (isset($subscription->errors)) {
                $errors = $subscription->errors;
                $status = false;
            }
        }
        return compact(['subscription', 'errors']);
    }

    public function updateLink(Subscription $subscription)
    {
        $link = $this->subscriptionRepo->generateUpdatePaymentProfileLink($subscription);
        return $link;
    }

    public function cancel(Subscription $subscription, array $data = [])
    {
        $result = $this->subscriptionRepo->cancelSubscription($subscription, $data);
        $subscription->cancelled_at = Carbon::now()->format('Y-m-d H:i:s');
        $subscription->save();
        return $result;

    }

    public function reactivate(Subscription $subscription)
    {
        $result = $this->subscriptionRepo->reactivateSubscription($subscription);
        $subscription->cancelled_at = null;
        $subscription->save();
        return $result;
    }
}