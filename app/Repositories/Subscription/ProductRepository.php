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
     * @return mixed
     */
    public function getProductsByProductFamilyID($product_family_id)
    {
        return Chargify::product()->allByProductFamily($product_family_id);
    }
}