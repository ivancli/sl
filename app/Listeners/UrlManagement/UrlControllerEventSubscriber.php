<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/03/2017
 * Time: 12:42 PM
 */

namespace App\Listeners\UrlManagement;


use App\Jobs\Log\UserActivity;

class UrlControllerEventSubscriber
{


    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {
        $activity = "Visited URLs Page";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeShow($event)
    {
        $url = $event->url;
    }

    public function onAfterShow($event)
    {
        $url = $event->url;
        $activity = "Loaded URL {$url->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
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
        $url = $event->url;
    }

    public function onBeforeEdit($event)
    {
        $url = $event->url;
    }

    public function onAfterEdit($event)
    {
        $url = $event->url;
        $activity = "Editing Item {$url->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeUpdate($event)
    {
        $url = $event->url;
    }

    public function onAfterUpdate($event)
    {
        $url = $event->url;
    }

    public function onBeforeDestroy($event)
    {
        $url = $event->url;
        $activity = "Deleting URL {$url->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onAfterDestroy($event)
    {
        $activity = "Deleted URL";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\Url\BeforeIndex',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterIndex',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\BeforeShow',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterShow',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\BeforeCreate',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterCreate',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\BeforeStore',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterStore',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\BeforeEdit',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterEdit',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\BeforeUpdate',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterUpdate',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\BeforeDestroy',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\AfterDestroy',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterDestroy'
        );
    }
}