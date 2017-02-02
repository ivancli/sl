<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:10 PM
 */

namespace App\Contracts\Repositories\Subscription;


interface ProductFamilyContract
{
    /**
     * Load all product families
     *
     * @return mixed
     */
    public function getProductFamilies();
}