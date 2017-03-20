<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/20/2017
 * Time: 11:02 AM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Item;
use App\Models\ItemMeta;

interface ItemMetaContract
{
    /**
     * Load all item metas (according to provided URL)
     * @param array $data
     * @param Item|null $item
     * @return mixed
     */
    public function filterAll(Array $data = [], Item $item = null);

    /**
     * Create new item meta
     * @param array $data
     * @return ItemMeta
     */
    public function store(Array $data = []);

    /**
     * Delete an item meta
     * @param ItemMeta $itemMeta
     * @return bool
     */
    public function destroy(ItemMeta $itemMeta);
}