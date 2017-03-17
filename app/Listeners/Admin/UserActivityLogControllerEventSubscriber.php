<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/17/2017
 * Time: 4:19 PM
 */
namespace App\Listeners\Admin;

class UserActivityLogControllerEventSubscriber
{

    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {

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

    public function onAfterStore()
    {

    }

    public function onBeforeEdit()
    {

    }

    public function onAfterEdit()
    {

    }

    public function onBeforeUpdate()
    {

    }

    public function onAfterUpdate()
    {

    }

    public function onBeforeDestroy()
    {

    }

    public function onAfterDestroy()
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\UserActivityLog\Index\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Index\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\Show\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Show\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\Create\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Create\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\Store\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Store\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\Edit\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Edit\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\Update\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Update\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\Destroy\Before',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\Destroy\After',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterDestroy'
        );
    }
}