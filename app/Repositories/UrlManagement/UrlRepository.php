<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 4:17 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\UrlContract;
use App\Models\Url;
use Illuminate\Http\Request;

class UrlRepository implements UrlContract
{
    protected $url;
    protected $request;

    public function __construct(Url $url, Request $request)
    {
        $this->url = $url;
        $this->request = $request;
    }

    /**
     * Load all url and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(Array $data)
    {

        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->url;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('full_path', 'LIKE', "%{$key}%");
            $builder->orWhere('status', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
            $builder->orWhere('updated_at', 'LIKE', "%{$key}%");
        }
        $urls = $builder->paginate($length);
        if ($urls->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $urls = $builder->paginate($length);
        }
        return $urls;
    }

    /**
     * load all URLs
     * @return mixed
     */
    public function all()
    {
        return $this->url->all();
    }

    /**
     * get one URL
     * @param $id
     * @param bool $throw
     * @return mixed
     */
    public function get($id, $throw = true)
    {
        if ($throw) {
            return $this->url->findOrFail($id);
        } else {
            return $this->url->find($id);
        }
    }

    /**
     * get URL by column
     * @param $column
     * @param $value
     * @return mixed
     */
    public function getBy($column, $value)
    {
        return $this->url->where($column, $value)->get();
    }

    /**
     * Create new URL
     * @param array $data
     * @return mixed
     */
    public function store(Array $data)
    {
        $url = new $this->url($data);
        $url->save();
        return $url;
    }

    /**
     * get single URL or create a new URL
     * @param array $data
     * @return mixed
     * @internal param $full_path
     */
    public function getByFullPathOrCreate(Array $data)
    {
        $urls = $this->getBy('full_path', $data['full_path']);
        if ($urls->count() == 0) {
            $url = $this->store($data);
        } else {
            $url = $urls->first();
        }
        return $url;
    }

    /**
     * Update existing URL
     * @param Url $url
     * @param array $data
     * @return mixed
     */
    public function update(Url $url, Array $data)
    {
        $url->status = $data['status'];
        $url->save();
        return $url;
    }

    /**
     * Remove an existing URL
     * @param Url $url
     * @return mixed
     */
    public function destroy(Url $url)
    {
        return $url->delete();
    }
}