<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 19/05/2017
 * Time: 5:25 PM
 */

namespace App\Validators\Alert;


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
            'product_alerts' => 'array',
            'category_alerts' => 'array',
            'product_alerts.*.product_id' => 'required',
            'product_alerts.*.type' => 'required',
            'product_alerts.*.price' => 'required_if:product_alerts.*.type,custom',
        ];
    }
}