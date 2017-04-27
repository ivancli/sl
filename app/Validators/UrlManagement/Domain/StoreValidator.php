<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/27/2017
 * Time: 11:36 AM
 */

namespace App\Validators\UrlManagement\Domain;


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
            'name' => "required|max:255",
            'full_path' => "required|url||unique:domains,full_path"
        ];
    }
}