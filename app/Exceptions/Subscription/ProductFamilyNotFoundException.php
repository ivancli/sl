<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/02/2017
 * Time: 4:42 PM
 */

namespace App\Exceptions\Subscription;


use App\Exceptions\NotFoundException;

class ProductFamilyNotFoundException extends NotFoundException
{
    public function __construct()
    {
        $this->setErrors(__('exceptions.Subscription.ProductNotFoundException'));
        parent::__construct();
    }
}