<?php
namespace App\Exceptions\Subscription;

use App\Exceptions\NotFoundException;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/02/2017
 * Time: 12:32 AM
 */
class ProductNotFoundException extends NotFoundException
{
    public function __construct()
    {
        $this->setErrors(__('exceptions.Subscription.ProductNotFoundException'));
        parent::__construct();
    }
}