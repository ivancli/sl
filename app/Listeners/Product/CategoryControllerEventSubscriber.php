<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/10/2017
 * Time: 5:00 PM
 */

namespace App\Listeners\Product;


use App\Jobs\Log\UserActivity;

class CategoryControllerEventSubscriber
{
    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Loaded Categories";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeShow()
    {

    }

    public function onAfterShow()
    {

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
        $category = $event->category;
        $activity = "Created Category {$category->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeEdit($event)
    {
        $category = $event->category;
    }

    public function onAfterEdit($event)
    {
        $category = $event->category;
        $activity = "Editing Category {$category->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeUpdate($event)
    {
        $category = $event->category;
    }

    public function onAfterUpdate($event)
    {
        $category = $event->category;
        $activity = "Updated Category {$category->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeDestroy($event)
    {
        $category = $event->category;
        $activity = "Deleting Category {$category->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    public function onAfterDestroy()
    {
        $activity = "Deleted Category";
        $this->dispatchUserActivityLog($activity);
    }

    public function onBeforeReportShow($event)
    {
        $category = $event->category;
    }

    public function onAfterReportShow($event)
    {
        $category = $event->category;
        $activity = "Loaded Product Report {$category->getKey()}";
        $this->dispatchUserActivityLog($activity);
    }

    protected function dispatchUserActivityLog($activity)
    {
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log")->onConnection('sync'));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Product\Category\BeforeIndex',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Product\Category\AfterIndex',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Product\Category\BeforeShow',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Product\Category\AfterShow',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Product\Category\BeforeCreate',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Product\Category\AfterCreate',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Product\Category\BeforeStore',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Product\Category\AfterStore',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Product\Category\BeforeEdit',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Product\Category\AfterEdit',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Product\Category\BeforeUpdate',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Product\Category\AfterUpdate',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Product\Category\BeforeDestroy',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Product\Category\AfterDestroy',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterDestroy'
        );
        $events->listen(
            'App\Events\Product\Category\BeforeReportShow',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeReportShow'
        );
        $events->listen(
            'App\Events\Product\Category\AfterReportShow',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterReportShow'
        );
    }
}