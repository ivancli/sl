<?php

namespace App\Repositories\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Exceptions\Subscription\CannotCreateSubscriptionException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;
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
     * @param array $data
     * @return mixed
     * @throws CannotCreateSubscriptionException
     */
    public function createSubscription(array $data)
    {
        $location = array_get($data, 'location');
        $subscription = Chargify::subscription($location)->create($data);
        if (isset($subscription->errors)) {
            throw new CannotCreateSubscriptionException($subscription->errors);
        }
        return $subscription;
    }

    /**
     * Update an existing subscription
     *
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function updateSubscription(Subscription $subscription, array $data)
    {
        $subscription = Chargify::subscription()->update($subscription->api_subscription_id, $data);
        return $subscription;
    }

    /**
     * Migrate an existing subscription to a new product
     *
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function migrateSubscription(Subscription $subscription, array $data)
    {
        $subscription = Chargify::subscription()->createMigration($subscription->api_subscription_id, $data);
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
        $message = "update_payment--{$subscription->api_subscription_id}--" . config("chargify.{$subscription->location}.api_share_key");
        $token = substr(sha1($message), 0, 10);
        $link = config("chargify.{$subscription->location}.api_domain") . "update_payment/{$subscription->api_subscription_id}/" . $token;
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

    public function cancelSubscription(Subscription $subscription, array $data = [])
    {
        $apiSubscription = $subscription->apiSubscription;

        /*TODO campaign monitor update*/
        dd($data);
        if (!array_has($data, 'keep_profile') || array_get($data, 'keep_profile') != '1') {
            Chargify::subscription($subscription->location)->deletePaymentProfile($apiSubscription->id, $apiSubscription->credit_card_id);
        }
        Chargify::subscription($subscription->location)->cancel($apiSubscription->id);
        $updatedSubscription = Chargify::subscription($subscription->location)->get($apiSubscription->id);
        if (!isset($updatedSubscription->errors)) {
            Cache::forget("{$subscription->location}.chargify.subscriptions.{$subscription->api_subscription_id}");

            /*TODO campaign monitor update*/

            /*TODO update campaign monitor next subscription plan*/

            return true;
        } else {
            return false;
        }
    }
}