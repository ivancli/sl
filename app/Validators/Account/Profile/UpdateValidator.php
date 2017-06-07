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
            'profile.title' => 'max:191',
            'profile.first_name' => 'required|max:191',
            'profile.last_name' => 'required|max:191',
            'company.industry' => 'required',
            'company.company_type' => 'required',
            'company.company_url' => 'max:2083|url|nullable',
            'company.ebay_username' => 'max:191',
            'display.DATE_FORMAT' => 'required|max:191',
            'display.TIME_FORMAT' => 'required|max:191',
        ];
    }
}