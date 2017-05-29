<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29/05/2017
 * Time: 11:40 PM
 */

namespace App\Validators\Subscription\Coupon;


use App\Validators\ValidatorAbstract;

class VerifyValidator extends ValidatorAbstract
{

    /**
     * Get pre-set validation rules
     *
     * @param null $id
     * @return array
     */
    protected function getRules($id = null)
    {
        return [
            'coupon_code' => 'required',
            'product_family_id' => 'required',
            'location' => 'required',
        ];
    }
}