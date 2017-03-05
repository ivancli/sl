<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/03/2017
 * Time: 12:42 PM
 */

namespace App\Listeners\UrlManagement;


class UrlControllerEventSubscriber
{


    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {

    }

    public function onBeforeShow($event)
    {
        $url = $event->url;
    }

    public function onAfterShow($event)
    {
        $url = $event->url;
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