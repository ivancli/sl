<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 3/15/2017
 * Time: 5:17 PM
 */

namespace App\Listeners\UrlManagement;


class ItemControllerEventSubscriber
{


    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {

    }

    public function onBeforeShow($event)
    {
        $item = $event->item;
    }

    public function onAfterShow($event)
    {
        $item = $event->item;
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
        $item = $event->item;
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
    }

    public function onBeforeDestroy($event)
    {
        $item = $event->item;
    }

    public function onAfterDestroy($event)
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\Url\Index\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Index\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\Show\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Show\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\Create\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Create\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\Store\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Store\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\Edit\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Edit\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\Update\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Update\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\Url\Destroy\Before',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\Url\Destroy\After',
            'App\Listeners\UrlManagement\UrlControllerEventSubscriber@onAfterDestroy'
        );
    }
}