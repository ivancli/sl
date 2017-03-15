<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/15/2017
 * Time: 5:32 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ItemContract;
use App\Models\Item;
use App\Models\Url;

class ItemRepository implements ItemContract
{

    /**
     * Load all item (according to provided URL)
     * @param Url|null $url
     * @return mixed
     */
    public function all(Url $url = null)
    {
        // TODO: Implement all() method.
    }

    /**
     * Load an item by item ID
     * @param $item_id
     * @return mixed
     */
    public function get($item_id)
    {
        // TODO: Implement get() method.
    }

    /**
     * Create a new item
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        // TODO: Implement store() method.
    }

    /**
     * Update an existing item
     * @param Item $item
     * @param array $data
     * @return mixed
     */
    public function update(Item $item, Array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete an existing item
     * @param Item $item
     * @return mixed
     */
    public function destroy(Item $item)
    {
        // TODO: Implement destroy() method.
    }
}