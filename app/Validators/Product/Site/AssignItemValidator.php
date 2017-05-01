<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/1/2017
 * Time: 11:38 AM
 */

namespace App\Validators\Product\Site;


use App\Validators\ValidatorAbstract;

class AssignItemValidator extends ValidatorAbstract
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
            'item_id' => 'required'
        ];
    }
}