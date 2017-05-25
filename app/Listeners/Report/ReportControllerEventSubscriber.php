<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 25/05/2017
 * Time: 9:57 AM
 */

namespace App\Listeners\Report;


use App\Jobs\Log\UserActivity;

class ReportControllerEventSubscriber
{

    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Reports";
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
        $activity = "Mass Updated Reports";
        $this->dispatchUserActivityLog($activity);
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
            'App\Events\Report\BeforeIndex',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Report\AfterIndex',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Report\BeforeShow',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Report\AfterShow',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Report\BeforeCreate',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Report\AfterCreate',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Report\BeforeStore',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Report\AfterStore',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Report\BeforeEdit',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Report\AfterEdit',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Report\BeforeUpdate',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Report\AfterUpdate',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Report\BeforeDestroy',
            'App\Listeners\Report\ReportControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Report\AfterDestroy',
            'App\Listeners\Report\ReportControllerEventSubscriber@onAfterDestroy'
        );
    }
}