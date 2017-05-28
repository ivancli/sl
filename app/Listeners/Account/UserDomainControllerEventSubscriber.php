<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 28/05/2017
 * Time: 3:34 PM
 */

namespace App\Listeners\UserManagement;


use App\Jobs\Log\UserActivity;

class UserDomainControllerEventSubscriber
{
    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {
        $activity = "Loaded User Domain";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow($event)
    {

    }

    public function onAfterShow($event)
    {

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
        $activity = "Updated User Domain";
        $this->dispatchUserActivityLog($activity);
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

    public function onAfterDestroy($event)
    {

    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeIndex',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterIndex',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeShow',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterShow',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeCreate',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterCreate',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeStore',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterStore',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeEdit',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterEdit',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeUpdate',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterUpdate',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UserManagement\UserDomain\BeforeDestroy',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UserManagement\UserDomain\AfterDestroy',
            'App\Listeners\Account\UserDomainControllerEventSubscriber@onAfterDestroy'
        );
    }
}