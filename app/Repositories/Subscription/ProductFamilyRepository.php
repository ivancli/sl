<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:19 PM
 */

namespace App\Repositories\Subscription;


use App\Contracts\Repositories\Subscription\ProductFamilyContract;
use Invigor\Chargify\Chargify;

class ProductFamilyRepository implements ProductFamilyContract
{
    /**
     * Load all product families
     *
     * @return mixed
     */
    public function getProductFamilies()
    {
        return Chargify::productFamily()->all();
    }
}