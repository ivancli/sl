<?php
namespace App\Listeners\Product;

use App\Jobs\Log\UserActivity;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/10/2017
 * Time: 5:00 PM
 */
class SiteControllerEventSubscriber
{


    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Sites";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow($event)
    {
        $site = $event->site;
    }

    public function onAfterShow($event)
    {
        $site = $event->site;
    }

    public function onBeforeCreate()
    {

    }

    public function onAfterCreate()
    {

    }

    public function onBeforeStore()
    {

    }

    public function onAfterStore($event)
    {
        $site = $event->site;
        $activity = "Created Site {$site->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeEdit($event)
    {
        $site = $event->site;
    }

    public function onAfterEdit($event)
    {
        $site = $event->site;
    }

    public function onBeforeUpdate($event)
    {
        $site = $event->site;
    }

    public function onAfterUpdate($event)
    {
        $site = $event->site;
        $activity = "Updated Site {$site->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeDestroy($event)
    {
        $site = $event->site;
        $activity = "Deleting Site {$site->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onAfterDestroy()
    {
        $activity = "Deleted Site";
        $this->dispatchUserActivityLog($activity);
    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Product\Site\BeforeIndex',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Product\Site\AfterIndex',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Product\Site\BeforeShow',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Product\Site\AfterShow',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Product\Site\BeforeCreate',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Product\Site\AfterCreate',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Product\Site\BeforeStore',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Product\Site\AfterStore',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Product\Site\BeforeEdit',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Product\Site\AfterEdit',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Product\Site\BeforeUpdate',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Product\Site\AfterUpdate',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Product\Site\BeforeDestroy',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Product\Site\AfterDestroy',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterDestroy'
        );
    }
}