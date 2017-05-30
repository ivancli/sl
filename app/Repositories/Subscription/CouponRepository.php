<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 30/05/2017
 * Time: 12:42 PM
 */

namespace App\Repositories\Subscription;


use App\Contracts\Repositories\Subscription\CouponContract;
use IvanCLI\Chargify\Chargify;

class CouponRepository implements CouponContract
{

    /**
     * verify the provided coupon code
     * @param array $data
     * @return mixed
     */
    public function verify(array $data = [])
    {
        $couponCode = array_get($data, 'coupon_code');
        $productFamilyId = array_get($data, 'product_family_id');
        $location = array_get($data, 'location', 'au');

        $coupon = Chargify::coupon($location)->validate($couponCode, $productFamilyId);

        if (!is_null($coupon) && !isset($coupon->errors) && is_null($coupon->archived_at)) {
            return true;
        } else {
            return false;
        }
    }
}