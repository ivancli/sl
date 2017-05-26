<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 26/05/2017
 * Time: 10:06 AM
 */

/**
 * Sanitizing string to file name safe format
 * @param $string
 * @return string
 */
function sanitizeFileName($string)
{
    $string = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $string);
    $string = mb_ereg_replace("([\.]{2,})", '', $string);
    $string = str_replace(' ', '_', $string);
    return $string;
}