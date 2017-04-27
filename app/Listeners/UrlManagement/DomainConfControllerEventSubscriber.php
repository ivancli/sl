<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 4/26/2017
 * Time: 2:24 PM
 */

namespace App\Listeners\UrlManagement;


use App\Jobs\Log\UserActivity;

class DomainConfControllerEventSubscriber
{
    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {

    }

    public function onBeforeShow($event)
    {
        $domain = $event->domain;
    }

    public function onAfterShow($event)
    {
        $domain = $event->domain;
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
        $domain = $event->domain;
    }

    public function onBeforeEdit($event)
    {
        $domain = $event->domain;
    }

    public function onAfterEdit($event)
    {
        $domain = $event->domain;
    }

    public function onBeforeUpdate($event)
    {
        $domain = $event->domain;
    }

    public function onAfterUpdate($event)
    {
        $domain = $event->domain;
        $activity = "Updated Domain Configuration {$domain->getKey()}";
        $this->dispatchUserActivityLog($activity);
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
            'App\Events\UrlManagement\DomainConf\BeforeIndex',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterIndex',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainConf\BeforeShow',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterShow',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainConf\BeforeCreate',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterCreate',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainConf\BeforeStore',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterStore',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainConf\BeforeEdit',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterEdit',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainConf\BeforeUpdate',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterUpdate',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainConf\BeforeDestroy',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainConf\AfterDestroy',
            'App\Listeners\UrlManagement\DomainConfControllerEventSubscriber@onAfterDestroy'
        );
    }
}