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

function sameDomain($url_1, $url_2)
{
    if (!is_null($url_1)) {
        $url1Segments = parse_url($url_1);
        $url1Domain = isset($url1Segments['host']) ? $url1Segments['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $url1Domain, $regs)) {
            $url1Domain = $regs['domain'];
        } else {
            return false;
        }

        $url2Segments = parse_url($url_2);
        $url2Domain = isset($url2Segments['host']) ? $url2Segments['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $url2Domain, $regs)) {
            $url2Domain = $regs['domain'];
        } else {
            return false;
        }
        return $url1Domain == $url2Domain;
    }

    return false;
}