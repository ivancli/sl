<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/13/2017
 * Time: 4:41 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


interface ParserContract
{
    /**
     * extract content from provided
     * @param $content
     * @param null $parserClassName
     * @param $options
     * @return mixed
     */
    public function extract($content, $options = [], $parserClassName = null);
}