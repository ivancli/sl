<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/13/2017
 * Time: 4:45 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ParserContract;
use App\Models\Item;
use App\Models\ItemMeta;

class ParserRepository implements ParserContract
{
    protected $parserClassPath = 'IvanCLI\Parser\Repositories\\';

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

    /**
     * Parse an item meta with its configuration
     * @param ItemMeta $itemMeta
     * @param $content
     * @param bool $save
     * @return mixed
     */
    public function parseMeta(ItemMeta $itemMeta, $content, $save = false)
    {
        $parserClassConfs = $itemMeta->getConfs('PARSER_CLASS');
        if ($parserClassConfs->count() == 0) {
            $parserClassName = "XPathParser";
        } else {
            $parserClassConf = $parserClassConfs->first();
            $parserClassName = $parserClassConf->value;
        }
        $parserClass = app()->make("{$this->parserClassPath}{$parserClassName}");
        $xpathConfs = $itemMeta->getConfs('XPATH');
        $extractions = [];
        foreach ($xpathConfs as $index => $xpathConf) {
            $parserClass->setContent($content);
            $parserClass->setOptions([
                "xpath" => $xpathConf->value,
            ]);
            $parserClass->extract();
            $extractions[] = $parserClass->getExtractions();
        }
        return $extractions;
    }
}