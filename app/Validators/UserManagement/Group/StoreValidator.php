<?php
namespace App\Validators\UserManagement\Group;

use App\Validators\ValidatorAbstract;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20/02/2017
 * Time: 8:15 PM
 */
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
            'name' => 'required|max:191|unique:groups',
            'display_name' => 'required|max:191',
            'description' => 'max:500',
        ];
    }
}