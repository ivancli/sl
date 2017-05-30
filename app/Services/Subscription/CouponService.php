<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29/05/2017
 * Time: 11:38 PM
 */

namespace App\Services\Subscription;


use App\Contracts\Repositories\Subscription\CouponContract;
use App\Validators\Subscription\Coupon\VerifyValidator;
use IvanCLI\Chargify\Chargify;

class CouponService
{
    #region repositories

    protected $couponRepo;

    #endregion

    #region validators

    protected $verifyValidator;

    #endregion

    public function __construct(CouponContract $couponContract, VerifyValidator $verifyValidator)
    {
        #region repositories binding
        $this->couponRepo = $couponContract;
        #endregion

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
        $result = $this->couponRepo->verify($data);

        return $result;
    }
}