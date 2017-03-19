<?php
namespace App\Listeners\Product;

use App\Jobs\Log\UserActivity;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/10/2017
 * Time: 5:00 PM
 */
class ProductControllerEventSubscriber
{

    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {
        $activity = "Visited Products Page";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeShow($event)
    {
        $product = $event->product;
    }

    public function onAfterShow($event)
    {
        $product = $event->product;
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
        $product = $event->product;
        $activity = "Created Product {$product->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeEdit($event)
    {
        $product = $event->product;
    }

    public function onAfterEdit($event)
    {
        $product = $event->product;
        $activity = "Editing Product {$product->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeUpdate($event)
    {
        $product = $event->product;
    }

    public function onAfterUpdate($event)
    {
        $product = $event->product;
        $activity = "Updated Product {$product->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onBeforeDestroy($event)
    {
        $product = $event->product;
        $activity = "Deleting Product {$product->getKey()}";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function onAfterDestroy()
    {
        $activity = "Deleted Product";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Product\Product\BeforeIndex',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Product\Product\AfterIndex',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Product\Product\BeforeShow',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Product\Product\AfterShow',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Product\Product\BeforeCreate',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Product\Product\AfterCreate',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Product\Product\BeforeStore',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Product\Product\AfterStore',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Product\Product\BeforeEdit',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Product\Product\AfterEdit',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Product\Product\BeforeUpdate',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Product\Product\AfterUpdate',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Product\Product\BeforeDestroy',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Product\Product\AfterDestroy',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterDestroy'
        );
    }
}