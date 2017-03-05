<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 5/03/2017
 * Time: 5:17 PM
 */

namespace App\Listeners\UrlManagement;


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
    }

    public function onBeforeUpdate($event)
    {
        $domain = $event->domain;
    }

    public function onAfterUpdate($event)
    {
        $domain = $event->domain;
    }

    public function onBeforeDestroy($event)
    {
        $domain = $event->domain;
    }

    public function onAfterDestroy($event)
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Index\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Index\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Show\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Show\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Create\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Create\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Store\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Store\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Edit\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Edit\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Update\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Update\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Destroy\Before',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\DomainMeta\Destroy\After',
            'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber@onAfterDestroy'
        );
    }
}