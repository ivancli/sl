<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 8:58 PM
 */

namespace App\Contracts\Repositories\Alert;


use App\Models\Alert;

interface HistoricalAlertContract
{
    /**
     * load filtered / all historical alerts
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * create historical alert
     * @param Alert $alert
     * @param array $data
     * @return mixed
     * @internal param $email
     */
    public function store(Alert $alert, array $data);
}