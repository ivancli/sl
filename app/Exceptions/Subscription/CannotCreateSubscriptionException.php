<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/7/2017
 * Time: 4:34 PM
 */

namespace App\Exceptions\Subscription;


use App\Exceptions\RequestException;
use Exception;

class CannotCreateSubscriptionException extends RequestException
{
    public function __construct(array $errors = [])
    {
        if (!empty($errors)) {
            $this->setErrors($errors);
        } else {
            $this->setErrors(__('exceptions.Subscription.CannotCreateSubscriptionException'));
        }
        parent::__construct();
    }
}