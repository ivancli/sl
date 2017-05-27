<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27/05/2017
 * Time: 8:06 PM
 */

/**
 * format a string into a currency
 * @param $string
 * @return string
 */
function currency($string)
{
    return number_format($string, 2, '.', ',');
}