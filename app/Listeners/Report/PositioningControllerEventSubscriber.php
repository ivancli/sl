<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/06/2017
 * Time: 4:40 PM
 */

namespace App\Listeners\Report;


use App\Jobs\Log\UserActivity;

class PositioningControllerEventSubscriber
{
    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Positioning Table";
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
            'App\Events\Positioning\BeforeIndex',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Positioning\AfterIndex',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Positioning\BeforeShow',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Positioning\AfterShow',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Positioning\BeforeCreate',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Positioning\AfterCreate',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Positioning\BeforeStore',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Positioning\AfterStore',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Positioning\BeforeEdit',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Positioning\AfterEdit',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Positioning\BeforeUpdate',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Positioning\AfterUpdate',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Positioning\BeforeDestroy',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Positioning\AfterDestroy',
            'App\Listeners\Report\PositioningControllerEventSubscriber@onAfterDestroy'
        );
    }
}