<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 31/05/2017
 * Time: 5:06 PM
 */

namespace App\Validators\Dashboard\Widget;


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
            'type' => 'required',
            'id' => 'required',
            'timespan' => 'required',
            'resolution' => 'required',
            'name' => 'required|max:191',
        ];
    }
}