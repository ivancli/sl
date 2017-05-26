<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 25/05/2017
 * Time: 11:19 AM
 */

namespace App\Contracts\Repositories\Report;


use App\Models\Report;

interface ReportContract
{
    /**
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data = []);

    /**
     * Get all reports
     * @param array $data
     * @return mixed
     */
    public function all(array $data = []);

    /**
     * Get report by report ID
     * @param $report_id
     * @param bool $throw
     * @return Report
     */
    public function get($report_id, $throw = true);

    /**
     * create an report
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * update report
     * @param array $data
     * @return mixed
     */
    public function massStore(array $data);

    /**
     * Update an existing report
     * @param Report $report
     * @param array $data
     * @return mixed
     */
    public function update(Report $report, array $data);

    /**
     * Generate a report
     * @param Report $report
     * @return mixed
     */
    public function generate(Report $report);
}