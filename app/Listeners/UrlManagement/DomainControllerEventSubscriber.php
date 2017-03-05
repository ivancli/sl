<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/03/2017
 * Time: 12:41 PM
 */

namespace App\Listeners\UrlManagement;


class DomainControllerEventSubscriber
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
            'App\Events\UrlManagement\Domain\Index\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Index\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\Show\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Show\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\Create\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Create\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\Store\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Store\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\Edit\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Edit\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\Update\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Update\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\Destroy\Before',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\Destroy\After',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterDestroy'
        );
    }
}