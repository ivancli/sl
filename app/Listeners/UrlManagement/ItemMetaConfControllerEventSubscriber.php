<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/21/2017
 * Time: 3:53 PM
 */

namespace App\Listeners\UrlManagement;


use App\Jobs\Log\UserActivity;

class ItemMetaConfControllerEventSubscriber
{


    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {
        $activity = "Visited Items Page";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeShow($event)
    {
        $item = $event->item;
    }

    public function onAfterShow($event)
    {
        $item = $event->item;
        $activity = "Loaded Item {$item->getKey()}";
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
        $activity = "Created Item Meta Configuration";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeEdit($event)
    {
        $item = $event->item;
    }

    public function onAfterEdit($event)
    {
        $item = $event->item;
    }

    public function onBeforeUpdate($event)
    {
        $item = $event->item;
    }

    public function onAfterUpdate($event)
    {
        $item = $event->item;
        $activity = "Updated Item Meta Configuration {$item->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeDestroy($event)
    {
        $item = $event->item;
        $activity = "Deleting Item Meta Configuration {$item->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onAfterDestroy($event)
    {
        $activity = "Deleted Item Meta Configuration";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeIndex',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterIndex',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeShow',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterShow',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeCreate',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterCreate',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeStore',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterStore',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeEdit',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterEdit',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeUpdate',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterUpdate',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\BeforeDestroy',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMetaConf\AfterDestroy',
            'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber@onAfterDestroy'
        );
    }
}