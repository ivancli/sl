<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:10 PM
 */

namespace App\Contracts\Repositories\Subscription;


use App\Exceptions\Subscription\ProductNotFoundException;

interface ProductContract
{
    /**
     * Load product by product ID
     *
     * @param $product_family_id
     * @param bool $throw
     * @return mixed
     * @throws ProductNotFoundException
     */
    public function getProductsByProductFamilyID($product_family_id, $throw = false);

    /**
     * Load product by product ID
     *
     * @param $product_id
     * @param bool $throw
     * @return mixed
     * @throws ProductNotFoundException
     */
    public function getProductByProductId($product_id, $throw = false);
}