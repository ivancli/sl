<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/15/2017
 * Time: 5:28 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Item;
use App\Models\Url;

interface ItemContract
{
    /**
     * Load all item (according to provided URL)
     * @param array $data
     * @param Url|null $url
     * @return mixed
     */
    public function filterAll(array $data = [], Url $url = null);

    /**
     * Load all items
     * @return mixed
     */
    public function all();

    /**
     * Load an item by item ID
     * @param $item_id
     * @param bool $throw
     * @return Item | null
     */
    public function get($item_id, $throw = true);

    /**
     * Create a new item
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update an existing item
     * @param Item $item
     * @param array $data
     * @return mixed
     */
    public function update(Item $item, array $data);

    /**
     * Delete an existing item
     * @param Item $item
     * @return mixed
     */
    public function destroy(Item $item);
}