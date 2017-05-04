<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/17/2017
 * Time: 11:33 AM
 */

namespace App\Validators\Product\Product;


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
        $this->validator->extendImplicit('unique_per_category', function ($message, $value, $parameters) use ($data) {
            $builder = auth()->user()->products();
            if (isset($data['category_id'])) {
                $builder->where('category_id', $data['category_id']);
            }
            if (is_array($parameters) && !is_null(array_first($parameters))) {
                $builder->where('id', '<>', array_first($parameters));
            }
            $currentProductNames = $builder->get()->map->product_name->all();
            return !in_array($value, $currentProductNames);
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
            'product_name' => "required|max:255|unique_per_category:{$id}",
            'meta.brand' => 'max:255',
            'meta.supplier' => 'max:255',
            'meta.sku' => 'max:255',
            'meta.cost_price' => 'numeric|nullable',
        ];
    }

}