<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/7/2017
 * Time: 4:14 PM
 */

namespace App\Exceptions\Subscription;


use App\Exceptions\NotFoundException;

class ProductSignUpPageNotFoundException extends NotFoundException
{
    public function __construct()
    {
        $this->setErrors(__('exceptions.Subscription.ProductSignUpPageNotFoundException'));
        parent::__construct();
    }
}