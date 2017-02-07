<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:10 PM
 */

namespace App\Contracts\Repositories\Subscription;


use App\Exceptions\Subscription\ProductNotFoundException;
use App\Exceptions\Subscription\ProductSignUpPageNotFoundException;
use App\Exceptions\Subscription\SubscriptionNotFoundException;
use App\Models\User;

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

    /**
     * Retrieve sign up page link of a product
     *
     * @param $product_id
     * @param User $user
     * @param string $coupon_code
     * @return mixed
     * @throws ProductSignUpPageNotFoundException
     * @throws SubscriptionNotFoundException
     */
    public function generateSignUpPageLink($product_id, User $user, $coupon_code = '');
}