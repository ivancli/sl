<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18/04/2017
 * Time: 11:12 PM
 */

namespace App\Listeners\Admin;


use App\Jobs\Log\UserActivity;

class AppPrefControllerEventSubscriber
{


    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Visited App Preferences";
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

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Admin\AppPref\BeforeIndex',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterIndex',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Admin\AppPref\BeforeShow',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterShow',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Admin\AppPref\BeforeCreate',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterCreate',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Admin\AppPref\BeforeStore',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterStore',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Admin\AppPref\BeforeEdit',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterEdit',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Admin\AppPref\BeforeUpdate',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterUpdate',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Admin\AppPref\BeforeDestroy',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Admin\AppPref\AfterDestroy',
            'App\Listeners\Admin\AppPrefControllerEventSubscriber@onAfterDestroy'
        );
    }
}