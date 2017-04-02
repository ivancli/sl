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
     * Parse an item meta with its configuration
     * @param ItemMeta $itemMeta
     * @param $content
     * @param bool $save
     * @return mixed
     */
    public function parseMeta(ItemMeta $itemMeta, $content, $save = false);

    /**
     * format the provided value as per provided type
     * @param $types
     * @param $value
     * @return mixed
     */
    public function formatMetaValue($types, $value);
}