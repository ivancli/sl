<?php

namespace App\Contracts\Repositories\Subscription;

use App\Exceptions\Subscription\CannotCreateSubscriptionException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Models\Subscription;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:07 PM
 */
interface SubscriptionContract
{
    /**
     * Submit preview subscription
     *
     * @param $data
     * @return mixed
     */
    public function previewSubscription($data);

    /**
     * Generate verification token in subscription table
     *
     * @return mixed
     */
    public function generateToken();

    /**
     * Create new subscription
     *
     * @param array $data
     * @return mixed
     */
    public function createSubscription(Array $data);

    /**
     * Update an existing subscription
     *
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function updateSubscription(Subscription $subscription, array $data);

    /**
     * Migrate an existing subscription to a new product
     *
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function migrateSubscription(Subscription $subscription, array $data);

    /**
     * Get subscription by Subscription ID
     *
     * @param $subscription_id
     * @param array $data
     * @return mixed *
     */
    public function get($subscription_id, array $data = []);

    /**
     * Generate update payment profile URL for a subscription
     * @param Subscription $subscription
     * @return mixed
     */
    public function generateUpdatePaymentProfileLink(Subscription $subscription);

    /**
     * Get all transactions of a subscription
     * @param Subscription $subscription
     * @return mixed
     */
    public function getTransactions(Subscription $subscription);

    /**
     * Cancel subscription
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function cancelSubscription(Subscription $subscription, array $data = []);

    /**
     * Reactivate subscription
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function reactivateSubscription(Subscription $subscription, array $data = []);
}