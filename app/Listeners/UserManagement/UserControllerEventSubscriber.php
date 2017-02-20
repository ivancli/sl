<?php
namespace App\Listeners\UserManagement;
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19/02/2017
 * Time: 9:42 PM
 */
class UserControllerEventSubscriber
{

    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {

    }

    public function onBeforeShow($event)
    {
        $user = $event->user;
    }

    public function onAfterShow($event)
    {
        $user = $event->user;
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
        $user = $event->user;
    }

    public function onBeforeEdit($event)
    {
        $user = $event->user;
    }

    public function onAfterEdit($event)
    {
        $user = $event->user;
    }

    public function onBeforeUpdate($event)
    {
        $user = $event->user;
    }

    public function onAfterUpdate($event)
    {
        $user = $event->user;
    }

    public function onBeforeDestroy($event)
    {
        $user = $event->user;
    }

    public function onAfterDestroy($event)
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserManagement\User\Index\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UserManagement\User\Index\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UserManagement\User\Show\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UserManagement\User\Show\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UserManagement\User\Create\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UserManagement\User\Create\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UserManagement\User\Store\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UserManagement\User\Store\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UserManagement\User\Edit\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UserManagement\User\Edit\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UserManagement\User\Update\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UserManagement\User\Update\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UserManagement\User\Destroy\Before',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UserManagement\User\Destroy\After',
            'App\Listeners\UserManagement\UserControllerEventSubscriber@onAfterDestroy'
        );
    }
}