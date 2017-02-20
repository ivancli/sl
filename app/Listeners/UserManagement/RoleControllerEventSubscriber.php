<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/20/2017
 * Time: 4:26 PM
 */

namespace App\Listeners\UserManagement;


class RoleControllerEventSubscriber
{

    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {

    }

    public function onBeforeShow($event)
    {
        $role = $event->role;
    }

    public function onAfterShow($event)
    {
        $role = $event->role;
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
        $role = $event->role;
    }

    public function onBeforeEdit($event)
    {
        $role = $event->role;
    }

    public function onAfterEdit($event)
    {
        $role = $event->role;
    }

    public function onBeforeUpdate($event)
    {
        $role = $event->role;
    }

    public function onAfterUpdate($event)
    {
        $role = $event->role;
    }

    public function onBeforeDestroy($event)
    {
        $role = $event->role;
    }

    public function onAfterDestroy($event)
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserManagement\Role\Index\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Index\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UserManagement\Role\Show\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Show\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UserManagement\Role\Create\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Create\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UserManagement\Role\Store\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Store\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UserManagement\Role\Edit\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Edit\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UserManagement\Role\Update\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Update\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UserManagement\Role\Destroy\Before',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UserManagement\Role\Destroy\After',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterDestroy'
        );
    }
}