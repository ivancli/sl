<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 9:13 PM
 */

namespace App\Listeners\Alert;


use App\Jobs\Log\UserActivity;
use App\Models\User;

class HistoricalAlertControllerEventSubscriber
{
    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Historical Alerts";
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
            'App\Events\Alert\BeforeIndex',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Alert\AfterIndex',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Alert\BeforeShow',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Alert\AfterShow',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Alert\BeforeCreate',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Alert\AfterCreate',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Alert\BeforeStore',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Alert\AfterStore',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Alert\BeforeEdit',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Alert\AfterEdit',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Alert\BeforeUpdate',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Alert\AfterUpdate',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Alert\BeforeDestroy',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Alert\AfterDestroy',
            'App\Listeners\Alert\HistoricalAlertControllerEventSubscriber@onAfterDestroy'
        );
    }
}