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
            $extractions[] = [
                'conf' => $xpathConf->value,
                'result' => $parserClass->getExtractions()
            ];
        }
        return $extractions;
    }

    /**
     * format the provided value as per provided type
     * @param $types
     * @param $value
     * @return mixed
     */
    public function formatMetaValue($types, $value)
    {
        if (is_array($types)) {
            foreach ($types as $type) {
                $value = $this->__formatMetaValue($type, $value);
                dump("called");
            }
        } else {
            $value = $this->__formatMetaValue($types, $value);
        }
        dump($value);
        return $value;
    }

    protected function __formatMetaValue($type, $value)
    {
        switch ($type) {
            case "strip_text":
                $stripTexts = config('strip_texts');
                foreach ($stripTexts as $stripText) {
                    $value = str_replace($stripTexts, '', $value);
                }
                break;
            case "currency":
                /*TODO remove words*/
                $value = preg_replace('#[^0-9,.]#', '', $value);
                /*TODO validate number format*/
                if (preg_grep('#(?=.)^\$?(([1-9][0-9]{0,2}(,[0-9]{3})*)|[0-9]+)?(\.[0-9]{1,2})?$#', $value)) {
                    /* international format*/
                    /* remove , */
                    $value = str_replace(',', '', $value);
                } elseif (preg_grep('#(?=.)^\$?(([1-9][0-9]{0,2}(.[0-9]{3})*)|[0-9]+)?(\,[0-9]{1,2})?$#', $value)) {
                    /* money format for Brazil, Denmark, Germany, Greece, Indonesia, Italy, Netherlands, Portugal, Romania, Russia, Slovenia, Sweden and much of Europe*/
                    /* remove . */
                    $value = str_replace('.', '', $value);

                } else {
                    /* money format is incorrect */
                    return false;
                }
                break;
            default:
                $value = trim($value);
        }
        return $value;
    }
}