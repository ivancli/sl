<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 30/05/2017
 * Time: 10:18 AM
 */

namespace App\Validators\Account\SampleAccount;


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
            'industry' => 'required',
            'company_type' => 'required',
            'company_url' => 'max:2083|url|nullable',
        ];
    }
}