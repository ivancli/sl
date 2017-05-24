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
            'basic_alert.type' => 'required_if:basic_alert.is_selected,true',
            'advanced_alert.product_alerts' => 'array',
            'advanced_alert.category_alerts' => 'array',
            'advanced_alert.category_alerts.*.category_id' => 'required_if:advanced_alert.is_selected,true',
            'advanced_alert.category_alerts.*.type' => 'required_if:advanced_alert.is_selected,true',
            'advanced_alert.product_alerts.*.product_id' => 'required_if:advanced_alert.is_selected,true',
            'advanced_alert.product_alerts.*.type' => 'required_if:advanced_alert.is_selected,true',
            'advanced_alert.product_alerts.*.price' => 'required_if:advanced_alert.product_alerts.*.type,custom|numeric|nullable',
        ];
    }
}