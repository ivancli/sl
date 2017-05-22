<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 19/05/2017
 * Time: 11:34 AM
 */

namespace App\Contracts\Repositories\Alert;


use App\Models\Alert;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

interface AlertContract
{
    /**
     * Get all alerts
     * @return mixed
     */
    public function all();

    /**
     * Get alert by alert ID
     * @param $alert_id
     * @param bool $throw
     * @return Alert
     */
    public function get($alert_id, $throw = true);

    /**
     * create/update an alert
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * mass create/update alerts
     * @param array $data
     * @return mixed
     */
    public function massStore(array $data);
}