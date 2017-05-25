<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Report as ReportModel;

class Report implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $report;
    protected $user;
    protected $lastActiveAt;
    protected $now;

    /**
     * Create a new job instance.
     *
     * @param ReportModel $report
     */
    public function __construct(ReportModel $report)
    {
        $this->report = $report;
        $this->user = $report->user;
        if (!is_null($this->report->last_active_at)) {
            $this->lastActiveAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->report->last_active_at);
        }
        $this->now = Carbon::now();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->report->report_type) {
            case 'product':
                break;
        }
    }

    protected function processProductReport()
    {
        switch ($this->report->reportable_type) {
            case 'product':
                $this->_processProductProductReport();
                break;
            case 'category':
                $this->_processProductCategoryReport();
                break;
        }
    }

    private function _processProductProductReport()
    {

    }

    private function _processProductCategoryReport()
    {

    }

    private function _ranWithinHours($hour = 1)
    {
        if (!is_null($this->lastActiveAt)) {
            $hours = $this->lastActiveAt->diffInHours($this->now);
            return $hours < $hour;
        }
        return false;
    }

    private function _isTimeToRun()
    {
        $frequency = $this->report->frequency;
        switch ($frequency) {
            case 'day':

                break;
            case 'week':

                break;
            case 'month':

                break;
        }
    }
}
