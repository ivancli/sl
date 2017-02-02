<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:10 PM
 */

namespace App\Contracts\Repositories\Subscription;


interface ProductContract
{
    /**
     * Load all product within a product family
     *
     * @param $product_family_id
     * @return mixed
     */
    public function getProductsByProductFamilyID($product_family_id);
}