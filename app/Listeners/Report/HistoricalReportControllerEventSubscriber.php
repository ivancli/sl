<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 10:39 PM
 */

namespace App\Listeners\Report;


use App\Jobs\Log\UserActivity;

class HistoricalReportControllerEventSubscriber
{
    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Historical Reports";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow()
    {

    }

    public function onAfterShow()
    {

    }

    public function onBeforeCreate()
    {

    }

    public function onAfterCreate()
    {

    }

    public function onBeforeStore()
    {

    }

    public function onAfterStore($event)
    {

    }

    public function onBeforeEdit($event)
    {

    }

    public function onAfterEdit($event)
    {

    }

    public function onBeforeUpdate($event)
    {

    }

    public function onAfterUpdate($event)
    {

    }

    public function onBeforeDestroy($event)
    {

    }

    public function onAfterDestroy()
    {

    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\HistoricalReport\BeforeIndex',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterIndex',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\HistoricalReport\BeforeShow',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterShow',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\HistoricalReport\BeforeCreate',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterCreate',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\HistoricalReport\BeforeStore',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterStore',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\HistoricalReport\BeforeEdit',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterEdit',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\HistoricalReport\BeforeUpdate',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterUpdate',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\HistoricalReport\BeforeDestroy',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\HistoricalReport\AfterDestroy',
            'App\Listeners\Report\HistoricalReportControllerEventSubscriber@onAfterDestroy'
        );
    }
}