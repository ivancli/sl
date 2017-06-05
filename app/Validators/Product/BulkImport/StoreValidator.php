<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 5/06/2017
 * Time: 2:09 PM
 */

namespace App\Validators\Product\BulkImport;


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
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ];
    }
}