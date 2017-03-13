<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/13/2017
 * Time: 4:45 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ParserContract;

class ParserRepository implements ParserContract
{

    /**
     * extract content from provided
     * @param $content
     * @param array $options
     * @param null $parserClassName
     * @return mixed
     */
    public function extract($content, $options = [], $parserClassName = null)
    {
        $parserClassPath = 'IvanCLI\Parser\Repositories\\';
        if (is_null($parserClassName)) {
            $parserClassName = "XPathParser";
        }
        $parserClass = app()->make("{$parserClassPath}$parserClassName");
        $parserClass->setContent($content);
        $parserClass->setOptions($options);
        $parserClass->extract();
        $extractions = $parserClass->getExtractions();
        return $extractions;
    }
}