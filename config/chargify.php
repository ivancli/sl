<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 2:04 PM
 */

return [
    //the api key generate in Chargify settings
    'api_key' => env('CHARGIFY_API_KEY'),

    //it's always 'x'
    'api_password' => env('CHARGIFY_API_PASSWORD', 'x'),

    //the domain of Chargify account
    'api_domain' => env('CHARGIFY_API_DOMAIN'),

    //the share key provided in Chargify settings used to generate links
    'api_share_key' => env("CHARGIFY_API_SHARE_KEY"),
];