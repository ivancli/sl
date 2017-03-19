<?php

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/17/2017
 * Time: 4:19 PM
 */
namespace App\Listeners\Admin;

use App\Jobs\Log\UserActivity;

class UserActivityLogControllerEventSubscriber
{

    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Visited User Activity Logs";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
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
            'App\Events\Admin\UserActivityLog\BeforeIndex',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterIndex',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\BeforeShow',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterShow',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\BeforeCreate',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterCreate',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\BeforeStore',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterStore',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\BeforeEdit',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterEdit',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\BeforeUpdate',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterUpdate',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Admin\UserActivityLog\BeforeDestroy',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Admin\UserActivityLog\AfterDestroy',
            'App\Listeners\Admin\UserActivityLogControllerEventSubscriber@onAfterDestroy'
        );
    }
}