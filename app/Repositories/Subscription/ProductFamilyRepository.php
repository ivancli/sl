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
     * @param array $data
     * @param bool $throw
     * @return mixed
     * @throws ProductFamilyNotFoundException
     */
    public function getProductFamilies(array $data = [], $throw = false)
    {
        $storeLocation = array_get($data, 'storeLocation', 'au');

        $productFamilies = Chargify::productFamily($storeLocation)->all();
        if ($throw && empty($productFamilies)) {
            throw new ProductFamilyNotFoundException();
        }
        return $productFamilies;
    }
}