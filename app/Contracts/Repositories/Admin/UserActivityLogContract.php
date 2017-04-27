<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/17/2017
 * Time: 5:29 PM
 */

namespace App\Contracts\Repositories\Admin;


interface UserActivityLogContract
{
    /**
     * Load all user activity logs
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * Create new user activity log
     * @param array $data
     * @return mixed
     */
    public function store(array $data = []);
}