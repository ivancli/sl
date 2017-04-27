<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/03/2017
 * Time: 12:41 PM
 */

namespace App\Listeners\UrlManagement;


use App\Jobs\Log\UserActivity;

class DomainControllerEventSubscriber
{
    public function onBeforeIndex($event)
    {

    }

    public function onAfterIndex($event)
    {
        $activity = "Visited Domains Page";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow($event)
    {
        $domain = $event->domain;
    }

    public function onAfterShow($event)
    {
        $domain = $event->domain;
        $activity = "Loaded Domain {$domain->getKey()}";
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
        $domain = $event->domain;
        $activity = "Created Domain {$domain->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeEdit($event)
    {
        $domain = $event->domain;
    }

    public function onAfterEdit($event)
    {
        $domain = $event->domain;
        $activity = "Editing Domain {$domain->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeUpdate($event)
    {
        $domain = $event->domain;
    }

    public function onAfterUpdate($event)
    {
        $domain = $event->domain;
        $activity = "Updated Domain {$domain->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeDestroy($event)
    {
        $domain = $event->domain;
        $activity = "Deleting Domain {$domain->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onAfterDestroy($event)
    {
        $activity = "Deleted Domain";
        $this->dispatchUserActivityLog($activity);
    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeIndex',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterIndex',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeShow',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterShow',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeCreate',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterCreate',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeStore',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterStore',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeEdit',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterEdit',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeUpdate',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterUpdate',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\UrlManagement\Domain\BeforeDestroy',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\UrlManagement\Domain\AfterDestroy',
            'App\Listeners\UrlManagement\DomainControllerEventSubscriber@onAfterDestroy'
        );
    }
}