<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/20/2017
 * Time: 11:04 AM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\ItemMetaContract;
use App\Models\Item;
use App\Models\ItemMeta;
use Illuminate\Http\Request;

class ItemMetaRepository implements ItemMetaContract
{
    protected $request;
    protected $itemMeta;

    public function __construct(Request $request, ItemMeta $itemMeta)
    {
        $this->request = $request;
        $this->itemMeta = $itemMeta;
    }

    /**
     * Load all item metas (according to provided URL)
     * @param array $data
     * @param Item|null $item
     * @return mixed
     */
    public function filterAll(array $data = [], Item $item = null)
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        if (!is_null($item)) {
            $builder = $item->metas();
        } else {
            $builder = $this->itemMeta;
        }
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('element', 'LIKE', "%{$key}%");
            $builder->orWhere('value', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
            $builder->orWhere('updated_at', 'LIKE', "%{$key}%");
        }
        $itemMetas = $builder->paginate($length);
        if ($itemMetas->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $itemMetas = $builder->paginate($length);
        }
        return $itemMetas;
    }

    /**
     * Load all item metas
     * @return mixed
     */
    public function all()
    {
        return $this->itemMeta->all();
    }

    /**
     * Load an item meta by id
     * @param $item_meta_id
     * @param bool $throw
     * @return mixed
     */
    public function get($item_meta_id, $throw = true)
    {
        if ($throw) {
            return $this->itemMeta->findOrFail($item_meta_id);
        } else {
            return $this->itemMeta->find($item_meta_id);
        }
    }

    /**
     * Create new item meta
     * @param array $data
     * @return ItemMeta
     */
    public function store(array $data = [])
    {
        $itemMeta = new $this->itemMeta;
        $itemMeta->element = $data['element'];
        $itemMeta->value = isset($data['value']) ? $data['value'] : null;
        $itemMeta->format_type = isset($data['format_type']) ? $data['format_type'] : null;
        $itemMeta->historical_type = isset($data['historical_type']) ? $data['historical_type'] : null;
        $itemMeta->status = isset($data['status']) ? $data['status'] : null;
        $itemMeta->save();
        return $itemMeta;
    }

    /**
     * Delete an item meta
     * @param ItemMeta $itemMeta
     * @return bool
     */
    public function destroy(ItemMeta $itemMeta)
    {
        return $itemMeta->delete();
    }

    /**
     * Update existing item meta
     * @param ItemMeta $itemMeta
     * @param array $data
     * @return mixed
     */
    public function update(ItemMeta $itemMeta, array $data)
    {
        $itemMeta->update($data);
        return $itemMeta;
    }
}