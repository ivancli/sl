<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16/02/2017
 * Time: 4:12 PM
 */

namespace App\Contracts\Repositories\UrlManagement;


use App\Models\Url;

interface UrlContract
{
    /**
     * Load all url and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data);

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
    public function getByFullPathOrCreate(array $data);

    /**
     * Create new URL
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update existing URL
     * @param Url $url
     * @param array $data
     * @return mixed
     */
    public function update(Url $url, array $data);

    /**
     * Remove an existing URL
     * @param Url $url
     * @return mixed
     */
    public function destroy(Url $url);
}