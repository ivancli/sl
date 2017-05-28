<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 9:07 PM
 */

namespace App\Services\Alert;


use App\Contracts\Repositories\Alert\HistoricalAlertContract;

class HistoricalAlertService
{
    #region repositories

    protected $historicalAlertRepo;

    #endregion

    public function __construct(HistoricalAlertContract $historicalAlertContract)
    {
        $this->historicalAlertRepo = $historicalAlertContract;
    }

    public function load(array $data = [])
    {
        $historicalAlerts = $this->historicalAlertRepo->filterAll($data);
        return $historicalAlerts;
    }
}