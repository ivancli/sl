<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 4:17 PM
 */

namespace App\Repositories\Product;


use App\Contracts\Repositories\Product\UrlContract;
use App\Models\Url;

class UrlRepository implements UrlContract
{
    var $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
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
}