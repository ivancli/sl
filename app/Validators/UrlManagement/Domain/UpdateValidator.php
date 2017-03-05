<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 5/03/2017
 * Time: 1:42 PM
 */

namespace App\Validators\UrlManagement\Domain;


use App\Validators\ValidatorAbstract;

class UpdateValidator extends ValidatorAbstract
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