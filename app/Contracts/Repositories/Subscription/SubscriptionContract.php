<?php
namespace App\Contracts\Repositories\Subscription;

use App\Exceptions\Subscription\CannotCreateSubscriptionException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;

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
     * @param $data
     * @return mixed
     * @throws CannotCreateSubscriptionException
     */
    public function createSubscription($data);

    /**
     * Get subscription by Subscription ID
     *
     * @param $subscription_id
     * @return mixed
     * * @throws SubscriptionNotFoundException
     */
    public function get($subscription_id);
}