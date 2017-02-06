<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:19 PM
 */

namespace App\Repositories\Subscription;


use App\Contracts\Repositories\Subscription\ProductContract;
use App\Contracts\Repositories\Subscription\ProductFamilyContract;
use App\Exceptions\Subscription\ProductNotFoundException;
use Invigor\Chargify\Chargify;

class ProductRepository implements ProductContract
{
    var $productFamilyRepo;

    public function __construct(ProductFamilyContract $productFamilyContract)
    {
        $this->productFamilyRepo = $productFamilyContract;
    }

    /**
     * Load all product within a product family
     *
     * @param $product_family_id
     * @param bool $throw
     * @return mixed
     * @throws ProductNotFoundException
     */
    public function getProductsByProductFamilyID($product_family_id, $throw = false)
    {
        $products = Chargify::product()->allByProductFamily($product_family_id);
        if ($throw && empty($products)) {
            throw new ProductNotFoundException();
        }
        return $products;
    }

    /**
     * Load product by product ID
     *
     * @param $product_id
     * @param bool $throw
     * @return mixed
     * @throws ProductNotFoundException
     */
    public function getProductByProductId($product_id, $throw = false)
    {
        $product = Chargify::product()->get($product_id);
        if ($throw && is_null($product)) {
            throw new ProductNotFoundException();
        }
        return $product;
    }
}