<?php
namespace App\Repositories\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionContract;
use App\Exceptions\Subscription\CannotCreateSubscriptionException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use Invigor\Chargify\Chargify;

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
}