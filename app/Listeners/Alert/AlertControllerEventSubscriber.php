<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 19/05/2017
 * Time: 11:28 AM
 */

namespace App\Listeners\Alert;


use App\Jobs\Log\UserActivity;

class AlertControllerEventSubscriber
{

    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Alerts";
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
        $activity = "Mass Updated Alerts";
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
            'App\Events\Alert\BeforeIndex',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Alert\AfterIndex',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Alert\BeforeShow',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Alert\AfterShow',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Alert\BeforeCreate',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Alert\AfterCreate',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Alert\BeforeStore',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Alert\AfterStore',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Alert\BeforeEdit',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Alert\AfterEdit',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Alert\BeforeUpdate',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Alert\AfterUpdate',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Alert\BeforeDestroy',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Alert\AfterDestroy',
            'App\Listeners\Alert\AlertControllerEventSubscriber@onAfterDestroy'
        );
    }
}