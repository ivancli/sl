<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21/03/2017
 * Time: 9:31 PM
 */

namespace App\Http\Controllers;


use App\Models\Report;
use Carbon\Carbon;

class TestController extends Controller
{
    protected $report;
    protected $user;
    protected $lastActiveAt;
    protected $now;

    protected $currentTime;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        $report = Report::first();
        $this->report = $report;
        $this->user = $report->user;
        if (!is_null($this->report->last_active_at)) {
            $this->lastActiveAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->report->last_active_at);
            $this->lastActiveAt->minute(0);
            $this->lastActiveAt->second(0);
        }
        $this->now = Carbon::now();
        $this->now->minute(0);
        $this->now->second(0);
        $this->currentTime = $this->now->format('H:i:s');
    }

    public function test()
    {
        switch ($this->report->report_type) {
            case 'product':
                $this->processProductReport();
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
        if ($this->_isTimeToRun()) {
            dd("called1");
        }
        dd("called2");
    }

    private function _processProductCategoryReport()
    {
        if ($this->_isTimeToRun()) {
            dd("called1");
        }
        dd("called2");
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
}