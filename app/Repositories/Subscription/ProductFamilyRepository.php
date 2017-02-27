<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:19 PM
 */

namespace App\Repositories\Subscription;


use App\Contracts\Repositories\Subscription\ProductFamilyContract;
use App\Exceptions\Subscription\ProductFamilyNotFoundException;
use IvanCLI\Chargify\Chargify;

class ProductFamilyRepository implements ProductFamilyContract
{
    /**
     * Load all product families
     *
     * @param bool $throw
     * @return mixed
     * @throws ProductFamilyNotFoundException
     */
    public function getProductFamilies($throw = false)
    {
        $productFamilies = Chargify::productFamily()->all();
        if ($throw && empty($productFamilies)) {
            throw new ProductFamilyNotFoundException();
        }
        return $productFamilies;
    }
}