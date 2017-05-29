<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29/05/2017
 * Time: 9:06 PM
 */

namespace App\Validators\Account\Profile;


use App\Validators\ValidatorAbstract;

class PasswordValidator extends ValidatorAbstract
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
            'password' => 'required|min:6|confirmed',
        ];
    }
}