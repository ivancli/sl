<?php
namespace App\Repositories\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Exceptions\Subscription\CannotCreateSubscriptionException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Models\Subscription;
use IvanCLI\Chargify\Chargify;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:08 PM
 */
class SubscriptionRepository implements SubscriptionContract
{
    /**
     * Submit preview subscription
     *
     * @param $data
     * @return mixed
     */
    public function previewSubscription($data)
    {
        $preview = Chargify::subscription()->preview($data);
        /* TODO add exception here */
        return $preview;
    }

    /**
     * Generate verification code in subscription table
     *
     * @return mixed
     */
    public function generateToken()
    {
        return str_random(10);
    }

    /**
     * Create new subscription
     *
     * @param $data
     * @return mixed
     * @throws CannotCreateSubscriptionException
     */
    public function createSubscription($data)
    {
        $subscription = Chargify::subscription()->create($data);
        /* TODO add exception here */
        if (isset($subscription->errors)) {
            throw new CannotCreateSubscriptionException($subscription->errors);
        }
        return $subscription;
    }

    /**
     * Get subscription by Subscription ID
     *
     * @param $subscription_id
     * @return mixed
     * @throws SubscriptionNotFoundException
     */
    public function get($subscription_id)
    {
        $subscription = Chargify::subscription()->get($subscription_id);
        if (is_null($subscription)) {
            throw new SubscriptionNotFoundException();
        }
        return $subscription;
    }

    /**
     * Generate update payment profile URL for a subscription
     * @param Subscription $subscription
     * @return mixed
     */
    public function generateUpdatePaymentProfileLink(Subscription $subscription)
    {
        $message = "update_payment--{$subscription->api_subscription_id}--" . config("chargify.api_share_key");
        $token = substr(sha1($message), 0, 10);
        $link = config('chargify.api_domain') . "update_payment/{$subscription->api_subscription_id}/" . $token;
        return $link;
    }

    /**
     * Get all transactions of a subscription
     * @param Subscription $subscription
     * @return mixed
     */
    public function getTransactions(Subscription $subscription)
    {
        $transactions = Chargify::transaction()->allBySubscription($subscription->api_subscription_id);
        $transactions = collect($transactions)->sortBy('created_at');
        return $transactions;
    }
}