<?php
namespace App\Repositories\Subscription;

use App\Contracts\Repositories\Subscription\SubscriptionContract;
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
        return Chargify::subscription()->preview($data);
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
}