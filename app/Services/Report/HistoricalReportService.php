<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 10:42 PM
 */

namespace App\Services\Report;


use App\Contracts\Repositories\Report\HistoricalReportContract;

class HistoricalReportService
{
    #region repositories

    protected $historicalReportRepo;

    #endregion

    public function __construct(HistoricalReportContract $historicalReportContract)
    {
        #region repositories binding
        $this->historicalReportRepo = $historicalReportContract;
        #endregion
    }

    /**
     * load historical reports by user
     * @param array $data
     * @return mixed
     */
    public function load(array $data = [])
    {
        $historicalReports = $this->historicalReportRepo->filterAll($data);
        return $historicalReports;
    }
}