<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 29/05/2017
 * Time: 9:35 AM
 */

namespace App\Services\Subscription;


use App\Contracts\Repositories\Subscription\SubscriptionManagementContract;

class ProductService
{
    #region repositories

    protected $subscriptionManagementRepo;

    #endregion

    public function __construct(SubscriptionManagementContract $subscriptionManagementContract)
    {
        #region repositories binding
        $this->subscriptionManagementRepo = $subscriptionManagementContract;
        #endregion
    }

    /**
     * Load pricing tables
     * @param array $data
     * @return mixed
     */
    public function prices(array $data = [])
    {
        $productFamilies = $this->subscriptionManagementRepo->getPricingTables($data);
        return $productFamilies;
    }
}