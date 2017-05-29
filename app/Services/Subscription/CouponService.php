<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29/05/2017
 * Time: 11:38 PM
 */

namespace App\Services\Subscription;


use App\Validators\Subscription\Coupon\VerifyValidator;
use IvanCLI\Chargify\Chargify;

class CouponService
{
    #region validators

    protected $verifyValidator;

    #endregion

    public function __construct(VerifyValidator $verifyValidator)
    {
        #region validators binding
        $this->verifyValidator = $verifyValidator;
        #endregion
    }

    /**
     * verify validity of a coupon within a product family
     * @param array $data
     * @return bool
     */
    public function verify(array $data = [])
    {
        $this->verifyValidator->validate($data);

        $couponCode = array_get($data, 'coupon_code');
        $productFamilyId = array_get($data, 'product_family_id');
        $location = array_get($data, 'subscription_location');

        $coupon = Chargify::coupon($location)->validate($couponCode, $productFamilyId);
        if (!isset($coupon->errors) && is_null($coupon->archived_at)) {
            return true;
        } else {
            return false;
        }
    }
}