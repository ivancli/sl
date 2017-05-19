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