<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/15/2017
 * Time: 5:17 PM
 */

namespace App\Listeners\UrlManagement;


use App\Jobs\Log\UserActivity;

class ItemMetaControllerEventSubscriber
{


    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {
        $activity = "Visited Item Metas Page";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow($event)
    {
        $itemMeta = $event->itemMeta;
    }

    public function onAfterShow($event)
    {
        $itemMeta = $event->itemMeta;
        $activity = "Loaded item meta {$itemMeta->getKey()}";
        $this->dispatchUserActivityLog($activity);
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
        $itemMeta = $event->itemMeta;
        $activity = "Created item meta {$itemMeta->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeEdit($event)
    {
        $itemMeta = $event->itemMeta;
    }

    public function onAfterEdit($event)
    {
        $itemMeta = $event->itemMeta;
        $activity = "Editing item meta {$itemMeta->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeUpdate($event)
    {
        $itemMeta = $event->itemMeta;
    }

    public function onAfterUpdate($event)
    {
        $itemMeta = $event->itemMeta;
        $activity = "Updated item meta {$itemMeta->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeDestroy($event)
    {
        $itemMeta = $event->itemMeta;
        $activity = "Deleting item meta {$itemMeta->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onAfterDestroy($event)
    {
        $activity = "Deleted item meta";
        $this->dispatchUserActivityLog($activity);
    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }


    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeIndex',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterIndex',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeShow',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterShow',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeCreate',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterCreate',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeStore',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterStore',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeEdit',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterEdit',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeUpdate',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterUpdate',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\ItemMeta\BeforeDestroy',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\ItemMeta\AfterDestroy',
            'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber@onAfterDestroy'
        );
    }
}