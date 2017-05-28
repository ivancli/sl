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

function domain($url)
{
    $urlSegments = parse_url($url);
    $urlDomain = isset($urlSegments['host']) ? $urlSegments['host'] : '';

    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $urlDomain, $regs)) {
        return $regs['domain'];
    } else {
        return null;
    }
}

function sameDomain($url_1, $url_2)
{
    if (!is_null($url_1)) {

        $url1Domain = domain($url_1);
        $url2Domain = domain($url_2);

        if (!is_null($url1Domain) && !is_null($url2Domain)) {
            return $url1Domain == $url2Domain;
        }
    }
    return false;
}