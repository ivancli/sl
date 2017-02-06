<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:10 PM
 */

namespace App\Contracts\Repositories\Subscription;


use App\Exceptions\Subscription\ProductFamilyNotFoundException;

interface ProductFamilyContract
{
    /**
     * Load all product families
     *
     * @param bool $throw
     * @return mixed
     * @throws ProductFamilyNotFoundException
     */
    public function getProductFamilies($throw = false);
}