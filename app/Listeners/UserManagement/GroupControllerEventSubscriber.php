<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/20/2017
 * Time: 4:23 PM
 */

namespace App\Listeners\UserManagement;


use App\Jobs\Log\UserActivity;

class GroupControllerEventSubscriber
{

    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {
        $activity = "Visited Groups Page";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow($event)
    {
        $group = $event->group;
    }

    public function onAfterShow($event)
    {
        $group = $event->group;
        $activity = "Loaded Group {$group->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeCreate($event)
    {

    }

    public function onAfterCreate($event)
    {

    }

    public function onBeforeStore($event)
    {

    }

    public function onAfterStore($event)
    {
        $group = $event->group;
        $activity = "Created Group {$group->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeEdit($event)
    {
        $group = $event->group;
    }

    public function onAfterEdit($event)
    {
        $group = $event->group;
        $activity = "Editing Group {$group->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeUpdate($event)
    {
        $group = $event->group;
    }

    public function onAfterUpdate($event)
    {
        $group = $event->group;
        $activity = "Updated Group {$group->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeDestroy($event)
    {
        $group = $event->group;
        $activity = "Deleting Group {$group->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onAfterDestroy($event)
    {

        $activity = "Loaded Group";
        $this->dispatchUserActivityLog($activity);
    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserManagement\Group\BeforeIndex',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterIndex',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UserManagement\Group\BeforeShow',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterShow',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UserManagement\Group\BeforeCreate',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterCreate',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UserManagement\Group\BeforeStore',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterStore',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UserManagement\Group\BeforeEdit',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterEdit',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UserManagement\Group\BeforeUpdate',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterUpdate',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UserManagement\Group\BeforeDestroy',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UserManagement\Group\AfterDestroy',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterDestroy'
        );
    }
}