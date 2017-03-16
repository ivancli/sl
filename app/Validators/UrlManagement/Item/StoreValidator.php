<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/03/2017
 * Time: 10:32 PM
 */
namespace App\Validators\UrlManagement\Item;

use App\Validators\ValidatorAbstract;

class StoreValidator extends ValidatorAbstract
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
            'url_id' => 'required'
        ];
    }
}