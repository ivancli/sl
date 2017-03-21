<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/21/2017
 * Time: 5:13 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\ItemMeta;

interface ItemMetaConfContract
{
    /**
     * Load all item meta confs
     * @return mixed
     */
    public function all();

    /**
     *
     * @param ItemMeta $itemMeta
     * @param array $data
     * @return mixed
     */
    public function store(ItemMeta $itemMeta, Array $data = []);
}