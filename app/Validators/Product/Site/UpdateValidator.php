<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/22/2017
 * Time: 3:11 PM
 */

namespace App\Validators\Product\Site;


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
        $this->validator->extendImplicit('unique_per_product',  function ($message, $value, $parameters)  use ($data) {
            $builder = auth()->user()->sites();
            if (isset($data['product_id'])) {
                $builder->where('product_id', $data['product_id']);
            }
            if (is_array($parameters) && !is_null(array_first($parameters))) {
                $builder->where('sites.id', '<>', array_first($parameters));
            }
            $currentSiteURLs = $builder->get()->map->siteUrl->all();
            return !in_array($value, $currentSiteURLs);
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
            "full_path" => "required|url|max:2083|unique_per_product:{$id}"
        ];
    }
}