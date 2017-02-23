<?php
namespace App\Validators\Account\Preference;

use App\Validators\ValidatorAbstract;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 5:15 PM
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
            'DATE_FORMAT' => 'required',
            'TIME_FORMAT' => 'required'
        ];
    }
}