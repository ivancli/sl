<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 5/03/2017
 * Time: 5:17 PM
 */

namespace App\Listeners\UrlManagement;


use App\Jobs\Log\UserActivity;

class DomainMetaControllerEventSubscriber
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
        $activity = "Editing Domain {$domain->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeUpdate($event)
    {
        $domain = $event->domain;
    }

    public function onAfterUpdate($event)
    {
        $domain = $event->domain;
        $activity = "Updated Domain {$domain->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeDestroy($event)
    {
        $domain = $event->domain;
        $activity = "Deleting Domain {$domain->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onAfterDestroy($event)
    {
        $activity = "Deleted Domain";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeIndex',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterIndex',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeShow',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterShow',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeCreate',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterCreate',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeStore',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterStore',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeEdit',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterEdit',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeUpdate',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterUpdate',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\BeforeDestroy',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\AfterDestroy',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterDestroy'
        );
    }
}