<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute is required.',
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute is required.',
    'required_if' => 'The :attribute is required when :other is :value.',
    'required_unless' => 'The :attribute is required unless :other is in :values.',
    'required_with' => 'The :attribute is required when :values is present.',
    'required_with_all' => 'The :attribute is required when :values is present.',
    'required_without' => 'The :attribute is required when :values is not present.',
    'required_without_all' => 'The :attribute is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'agree_terms' => [
            'required' => 'You need to agree to the terms to proceed.'
        ],
        'subscription_plan_id' => [
            'required' => 'Please select a subscription plan.'
        ],
        'name' => [
            'unique_per_user' => 'Name needs to be unique.',
            'unique_per_category' => 'Name needs to be unique in a category.',
        ],
        'category_name' => [
            'unique_per_user' => 'A category with the same name already exists.',
        ],
        'product_name' => [
            'unique_per_user' => 'A product with the same name already exists.',
            'unique_per_category' => 'A product with the same name already exists in this category.',
        ],
        'full_path' => [
            'required' => 'URL is required.',
            'url' => 'Please provide a valid URL.',
            'unique' => 'Same URL already exists.',
            'unique_per_product' => 'A site with the same URL already exists in this product.'
        ],
        'DATE_FORMAT' => [
            'required' => 'Please select a date format.',
        ],
        'TIME_FORMAT' => [
            'required' => 'Please select a time format.',
        ],
        /*TODO find a way to make message appear once only, or add index to message*/
        'metas.*.name' => [
            'required' => 'Each meta\'s name is required.',
        ],
        'metas.*.type' => [
            'required' => 'Each meta\'s type is required.',
        ],
        'confs.*.element' => [
            'required' => 'Each configuration\'s element is required.',
        ],
        'confs.*.value' => [
            'required' => 'Each configuration\'s value is required.',
        ],
        'product_alerts.*.type' => [
            'required' => 'Product alert type is required.',
        ],
        'product_alerts.*.price' => [
            'required' => 'Price is required.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'meta.brand' => 'product brand name',
        'meta.supplier' => 'product supplier name',
        'meta.sku' => 'product SKU',
        'meta.cost_price' => 'product cost price',
        'profile.title' => 'title',
        'profile.first_name' => 'first name',
        'profile.last_name' => 'last name',
        'profile.email' => 'email',
        'company.industry' => 'industry',
        'company.company_type' => 'company type',
        'company.company_url' => 'my site URL',
        'company.ebay_username' => 'eBay username',
        'display.date_format' => 'date format',
        'display.time_format' => 'time format',
        'product_alerts.*.product_id' => 'product ID',
    ],

];
