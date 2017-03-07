<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/7/2017
 * Time: 5:10 PM
 */

namespace App\Contracts\Repositories\Admin;


use App\Models\AppPref;

interface AppPrefContract
{
    /**
     * Load all application preferences
     * @return mixed
     */
    public function all();

    /**
     * Load an application preference by element
     * @param $element
     * @return mixed
     */
    public function get($element);

    /**
     * Create or update an application preference
     * @param array $data
     * @return mixed
     */
    public function store(Array $data);

    /**
     * Remove an existing application preference
     * @param AppPref $appPref
     * @return mixed
     */
    public function destroy(AppPref $appPref);
}