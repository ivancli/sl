<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/20/2017
 * Time: 4:23 PM
 */

namespace App\Listeners\UserManagement;


class GroupControllerEventSubscriber
{

    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {

    }

    public function onBeforeShow($event)
    {
        $group = $event->group;
    }

    public function onAfterShow($event)
    {
        $group = $event->group;
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
    }

    public function onBeforeEdit($event)
    {
        $group = $event->group;
    }

    public function onAfterEdit($event)
    {
        $group = $event->group;
    }

    public function onBeforeUpdate($event)
    {
        $group = $event->group;
    }

    public function onAfterUpdate($event)
    {
        $group = $event->group;
    }

    public function onBeforeDestroy($event)
    {
        $group = $event->group;
    }

    public function onAfterDestroy($event)
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserManagement\Group\Index\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Index\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UserManagement\Group\Show\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Show\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UserManagement\Group\Create\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Create\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UserManagement\Group\Store\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Store\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UserManagement\Group\Edit\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Edit\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UserManagement\Group\Update\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Update\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UserManagement\Group\Destroy\Before',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UserManagement\Group\Destroy\After',
            'App\Listeners\UserManagement\GroupControllerEventSubscriber@onAfterDestroy'
        );
    }
}