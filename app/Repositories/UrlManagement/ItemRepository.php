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
use Illuminate\Http\Request;

class ItemRepository implements ItemContract
{
    var $item;
    var $request;

    public function __construct(Item $item, Request $request)
    {
        $this->item = $item;
        $this->request = $request;
    }

    /**
     * Load all item (according to provided URL)
     * @param array $data
     * @param Url|null $url
     * @return mixed
     */
    public function filterAll(Array $data = [], Url $url = null)
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        if (!is_null($url)) {
            $builder = $url->items();
        } else {
            $builder = $this->item;
        }
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('name', 'LIKE', "%{$key}%");
            $builder->orWhere('is_active', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
            $builder->orWhere('updated_at', 'LIKE', "%{$key}%");
        }
        $items = $builder->paginate($length);
        if ($items->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $items = $builder->paginate($length);
        }
        return $items;
    }

    /**
     * Load an item by item ID
     * @param $item_id
     * @param bool $throw
     * @return Item | null
     */
    public function get($item_id, $throw = true)
    {
        if ($throw) {
            return $this->item->findOrFail($item_id);
        } else {
            return $this->item->find($item_id);
        }
    }

    /**
     * Create a new item
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        $item = new $this->item;
        $item->name = isset($data['name']) ? $data['name'] : null;
        $item->is_active = isset($data['is_active']) ? $data['is_active'] : null;
        $item->save();
        return $item;
    }

    /**
     * Update an existing item
     * @param Item $item
     * @param array $data
     * @return mixed
     */
    public function update(Item $item, Array $data)
    {
        $item->update($data);
        return $item;
    }

    /**
     * Delete an existing item
     * @param Item $item
     * @return mixed
     */
    public function destroy(Item $item)
    {
        return $item->delete();
    }
}