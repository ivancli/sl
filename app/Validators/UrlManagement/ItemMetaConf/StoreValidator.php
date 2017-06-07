<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/21/2017
 * Time: 5:26 PM
 */
namespace App\Validators\UrlManagement\ItemMetaConf;

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
            'item_meta_id' => 'required',
            'confs.*.element' => 'required|max:191',
            'confs.*.value' => 'required|max:191',
        ];
    }
}