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
     * Migrate an existing subscription to a new product
     *
     * @param Subscription $subscription
     * @param array $data
     * @return mixed
     */
    public function migrateSubscription(Subscription $subscription, Array $data);

    /**
     * Get subscription by Subscription ID
     *
     * @param $subscription_id
     * @return mixed
     * * @throws SubscriptionNotFoundException
     */
    public function get($subscription_id);

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
}