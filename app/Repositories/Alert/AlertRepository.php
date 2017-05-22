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
     * Get all alerts
     * @return mixed
     */
    public function all()
    {
        return Alert::all();
    }

    /**
     * Get alert by alert ID
     * @param $alert_id
     * @param bool $throw
     * @return Alert
     */
    public function get($alert_id, $throw = true)
    {
        if ($throw == true) {
            return Alert::findOrFail($alert_id);
        } else {
            return Alert::find($alert_id);
        }
    }

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