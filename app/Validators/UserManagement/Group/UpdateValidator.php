<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20/02/2017
 * Time: 9:48 PM
 */

namespace App\Validators\UserManagement\Group;


use App\Validators\ValidatorAbstract;

class UpdateValidator extends ValidatorAbstract
{
    /**
     * Insert new rule method to validator
     *
     * @param array $data
     * @param bool $throw
     * @return bool|\Illuminate\Support\MessageBag
     */
    public function validate(array $data, $throw = true)
    {
        $rules = $this->getRules($data['id']);
        $validation = $this->validator->make($data, $rules);
        if ($validation->fails()) {
            if ($throw) {
                $this->throwValidationException($validation);
            } else {
                return $validation->messages();
            }

        }
        return true;
    }

    /**
     * Get pre-set validation rules
     *
     * @param null $id
     * @return array
     */
    protected function getRules($id = null)
    {
        return [
            'name' => "required|max:255|unique:groups,name,{$id},id",
            'display_name' => 'required|max:255',
            'description' => 'max:500',
        ];
    }
}