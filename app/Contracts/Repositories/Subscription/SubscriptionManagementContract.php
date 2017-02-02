<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 1:36 PM
 */

namespace App\Contracts\Repositories\Subscription;


interface SubscriptionManagementContract
{
    /**
     * Retrieve data for pricing tables
     *
     * @return mixed
     */
    public function getPricingTables();
}