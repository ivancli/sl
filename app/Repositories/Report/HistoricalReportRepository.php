<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 26/05/2017
 * Time: 10:18 AM
 */

namespace App\Repositories\Report;


use App\Contracts\Repositories\Report\HistoricalReportContract;
use App\Models\HistoricalReport;

class HistoricalReportRepository implements HistoricalReportContract
{
    protected $historicalReport;

    public function __construct(HistoricalReport $historicalReport)
    {
        $this->historicalReport = $historicalReport;
    }

    /**
     *
     * @param array $data
     * @return \App\Models\HistoricalReport
     */
    public function store(array $data)
    {
        $historicalRepo = $this->historicalReport->create($data);
        return $historicalRepo;
    }
}