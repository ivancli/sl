<?php
namespace App\Listeners\Account;
use App\Jobs\Log\UserActivity;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 1:23 PM
 */
class ProfileControllerEventSubscriber
{
    public function onBeforeShow($event)
    {
        $user = $event->user;
    }

    public function onAfterShow($event)
    {
        $user = $event->user;
        $activity = "Visited Account Profile";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeUpdate($event)
    {
        $user = $event->user;
    }

    public function onAfterUpdate($event)
    {
        $user = $event->user;
        $activity = "Updated Account Profile";
        $this->dispatchUserActivityLog($activity);
    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }


    /**
     * register events for the subscriber
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Account\Profile\BeforeShow',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onBeforeShow'
        );

        $events->listen(
            'App\Events\Account\Profile\AfterShow',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Account\Profile\BeforeUpdate',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onBeforeUpdate'
        );

        $events->listen(
            'App\Events\Account\Profile\AfterUpdate',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onAfterUpdate'
        );
    }
}