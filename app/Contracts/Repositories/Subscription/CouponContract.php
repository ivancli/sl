<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 30/05/2017
 * Time: 12:41 PM
 */

namespace App\Contracts\Repositories\Subscription;


interface CouponContract
{
    /**
     * verify the provided coupon code
     * @param array $data
     * @return mixed
     */
    public function verify(array $data = []);
}