<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/20/2017
 * Time: 2:10 PM
 */

namespace App\Validators\UrlManagement\ItemMeta;

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
            'element' => 'required|max:255',
            'value' => 'max:255'
        ];
    }
}