<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11/03/2017
 * Time: 10:48 PM
 */

return [
    'delay' => [
        'min' => env('CRAWL_DELAY_MIN', 1),
        'max' => env('CRAWL_DELAY_MAX', 900),
    ],
];