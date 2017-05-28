<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 26/05/2017
 * Time: 10:17 AM
 */

namespace App\Contracts\Repositories\Report;


interface HistoricalReportContract
{

    /**
     * load all / filtered historical reports
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * create new historical report
     * @param array $data
     * @return \App\Models\HistoricalReport
     */
    public function store(array $data);
}