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
     * Load an item meta by id
     * @param $item_meta_id
     * @param bool $throw
     * @return mixed
     */
    public function get($item_meta_id, $throw = true);

    /**
     * Create new item meta
     * @param array $data
     * @return ItemMeta
     */
    public function store(Array $data = []);

    /**
     * Update existing item meta
     * @param ItemMeta $itemMeta
     * @param array $data
     * @return mixed
     */
    public function update(ItemMeta $itemMeta, Array $data);

    /**
     * Delete an item meta
     * @param ItemMeta $itemMeta
     * @return bool
     */
    public function destroy(ItemMeta $itemMeta);
}