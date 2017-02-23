<?php
namespace App\Validators\Account\Profile;

use App\Validators\ValidatorAbstract;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 1:16 PM
 */
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'industry' => 'required',
            'company_type' => 'required',
            'company_url' => 'max:2083'
        ];
    }
}