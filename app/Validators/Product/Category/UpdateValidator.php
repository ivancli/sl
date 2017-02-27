<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/17/2017
 * Time: 10:33 AM
 */

namespace App\Validators\Product\Category;


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
        $this->validator->extendImplicit('unique_per_user', function ($message, $value, $parameters) {
            $builder = auth()->user()->categories();
            if (is_array($parameters) && !is_null(array_first($parameters))) {
                $builder->where('id', '<>', array_first($parameters));
            }
            $currentCategoryNames = $builder->get()->map->category_name->all();
            return !in_array($value, $currentCategoryNames);
        });

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
            'category_name' => "required|max:255|unique_per_user:{$id}"
        ];
    }
}