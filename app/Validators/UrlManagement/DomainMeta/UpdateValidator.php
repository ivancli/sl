<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/6/2017
 * Time: 11:52 AM
 */

namespace App\Validators\UrlManagement\DomainMeta;


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
            'metas.*.element' => 'required|max:255',
            'metas.*.format_type' => 'max:255',
            'metas.*.historical_type' => 'max:255',
        ];
    }
}