<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 4:12 PM
 */

namespace App\Contracts\Repositories\Product;


use phpDocumentor\Reflection\Types\Array_;

interface UrlContract
{
    /**
     * load all URLs
     * @return mixed
     */
    public function all();

    /**
     * get one URL
     * @param $id
     * @param bool $throw
     * @return mixed
     */
    public function get($id, $throw = true);

    /**
     * get URLs by column
     * @param $column
     * @param $value
     * @return mixed
     */
    public function getBy($column, $value);

    /**
     * get single URL or create a new URL
     * @param array $data
     * @return mixed
     * @internal param $full_path
     */
    public function getByFullPathOrCreate(Array $data);

    /**
     * Create new URL
     * @param array $data
     * @return mixed
     */
    public function store(Array $data);
}