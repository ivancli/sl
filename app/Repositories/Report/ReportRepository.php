<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 25/05/2017
 * Time: 11:20 AM
 */

namespace App\Repositories\Report;


use App\Contracts\Repositories\Report\ReportContract;
use App\Models\Report;

class ReportRepository implements ReportContract
{
    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get all reports
     * @return mixed
     */
    public function all()
    {
        return $this->report->all();
    }

    /**
     * Get report by report ID
     * @param $report_id
     * @param bool $throw
     * @return Report
     */
    public function get($report_id, $throw = true)
    {
        if ($throw == true) {
            return $this->report->findOrFail($report_id);
        } else {
            return $this->report->find($report_id);
        }
    }

    /**
     * create an report
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $report = Report::create($data);
        return $report;
    }

    /**
     * update reports
     * @param array $data
     * @return mixed
     */
    public function massStore(array $data)
    {
        // TODO: Implement massStore() method.
    }

    /**
     * Update an existing report
     * @param Report $report
     * @param array $data
     * @return mixed
     */
    public function update(Report $report, array $data)
    {
        $report->update($data);
        return $report;
    }
}