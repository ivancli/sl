<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/13/2017
 * Time: 4:41 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\ItemMeta;

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

    /**
     * Parse an item meta with its configuration
     * @param ItemMeta $itemMeta
     * @param $content
     * @param bool $save
     * @return mixed
     */
    public function parseMeta(ItemMeta $itemMeta, $content, $save = false);
}