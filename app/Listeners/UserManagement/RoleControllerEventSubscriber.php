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
            'App\Events\UserManagement\Role\BeforeIndex',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterIndex',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UserManagement\Role\BeforeShow',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterShow',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UserManagement\Role\BeforeCreate',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterCreate',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UserManagement\Role\BeforeStore',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterStore',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UserManagement\Role\BeforeEdit',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterEdit',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UserManagement\Role\BeforeUpdate',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterUpdate',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UserManagement\Role\BeforeDestroy',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UserManagement\Role\AfterDestroy',
            'App\Listeners\UserManagement\RoleControllerEventSubscriber@onAfterDestroy'
        );
    }
}