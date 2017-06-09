<?php

namespace App\Jobs;

use App\Contracts\Repositories\Report\ReportContract;
use App\Mail\Report\DigestReport;
use App\Mail\Report\ProductCategoryReport;
use App\Mail\Report\ProductProductReport;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Report as ReportModel;
use Illuminate\Support\Facades\Mail;

class Report implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $report;

    protected $reportRepo;

    protected $reportable;

    protected $user;
    protected $lastActiveAt;
    protected $now;
    protected $currentTime;

    /**
     * Create a new job instance.
     *
     * @param ReportModel $report
     */
    public function __construct(ReportModel $report)
    {
        $this->report = $report;

        $this->reportable = $report->reportable;

        $this->user = $report->user;

        if (!is_null($this->report->last_active_at)) {
            $this->lastActiveAt = Carbon::parse($this->report->last_active_at)->minute(0)->second(0);
        }
        $this->now = Carbon::now()->minute(0)->second(0);
        $this->currentTime = $this->now->format('H:i:s');

        $this->reportRepo = app(ReportContract::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->_validateSubscription($this->report)) {
            return;
        }
        if (is_null($this->reportable)) {
            return;
        }

        switch ($this->report->report_type) {
            case 'product':
                $this->processProductReport();
                break;
            case 'digest':
                $this->processDigestReport();
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

    protected function processDigestReport()
    {
        if ($this->_isTimeToRun()) {
            $reportDetail = $this->reportRepo->generate($this->report);
            Mail::to($this->user->email)
                ->send(new DigestReport($this->report, $reportDetail));
        }
        $this->report->setLastActiveAt();
    }

    private function _processProductProductReport()
    {
        if ($this->_isTimeToRun()) {
            $historicalReport = $this->reportRepo->generate($this->report);
            Mail::to($this->user->email)
                ->send(new ProductProductReport($this->report, $historicalReport));
        }
        $this->report->setLastActiveAt();
    }

    private function _processProductCategoryReport()
    {
        if ($this->_isTimeToRun()) {
            $historicalReport = $this->reportRepo->generate($this->report);
            Mail::to($this->user->email)
                ->send(new ProductCategoryReport($this->report, $historicalReport));
        }
        $this->report->setLastActiveAt();
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
        if ($this->_ranWithinHours()) {
            return false;
        }

        $frequency = $this->report->frequency;
        switch ($frequency) {
            case 'day':
                if ($this->report->time == $this->currentTime) {
                    if ($this->report->weekday_only == 'y') {
                        if ($this->now->isWeekday()) {
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
                return false;
                break;
            case 'week':
                /* check same day of week */
                if ($this->report->day == $this->now->dayOfWeek) {
                    /* check same hour */
                    if ($this->report->time == $this->currentTime) {
                        if ($this->report->weekday_only == 'y') {
                            /* check weekday */
                            if ($this->now->isWeekday()) {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    }
                }
                return false;
                break;
            case 'month':
                /* check same date of month */
                if ($this->report->date == $this->now->day) {
                    /* check same hour */
                    if ($this->report->time == $this->currentTime) {
                        if ($this->report->weekday_only == 'y') {
                            /* check weekday */
                            if ($this->now->isWeekday()) {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    }
                }
                break;
        }
        return true;
    }

    private function _validateSubscription(ReportModel $report)
    {
        $user = $report->user;
        if (!is_null($user->subscription)) {
            return $user->subscription->isValid;
        }
        return true;
    }
}
