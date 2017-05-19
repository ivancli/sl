<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 19/05/2017
 * Time: 11:38 AM
 */

namespace App\Repositories\Alert;


use App\Contracts\Repositories\Alert\AlertContract;
use App\Models\Alert;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class AlertRepository implements AlertContract
{
    /**
     * create/update an alert
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $alert = Alert::create($data);
        return $alert;
    }

    /**
     * mass create/update alerts
     * @param array $data
     * @return mixed
     */
    public function massStore(array $data)
    {
        // TODO: Implement massStore() method.
    }
}