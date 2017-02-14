<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/14/2017
 * Time: 10:44 AM
 */
namespace App\Validators\Product\Product;

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
            'name' => 'required|max:255'
        ];
    }
}