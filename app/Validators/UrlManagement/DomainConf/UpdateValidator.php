<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/26/2017
 * Time: 3:03 PM
 */
namespace App\Validators\UrlManagement\DomainConf;

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
            'confs.*.element' => 'required|max:191',
            'confs.*.value' => 'required|max:191',
        ];
    }
}