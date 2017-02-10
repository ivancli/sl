<?php
namespace App\Listeners\Product;
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

    public function onAfterStore()
    {

    }

    public function onBeforeEdit()
    {

    }

    public function onAfterEdit()
    {

    }

    public function onBeforeUpdate()
    {

    }

    public function onAfterUpdate()
    {

    }

    public function onBeforeDestroy()
    {

    }

    public function onAfterDestroy()
    {

    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Product\Product\Index\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Product\Product\Index\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Product\Product\Show\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Product\Product\Show\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Product\Product\Create\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Product\Product\Create\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Product\Product\Store\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Product\Product\Store\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Product\Product\Edit\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Product\Product\Edit\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Product\Product\Update\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Product\Product\Update\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Product\Product\Destroy\Before',
            'App\Listeners\Product\ProductControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Product\Product\Destroy\After',
            'App\Listeners\Product\ProductControllerEventSubscriber@onAfterDestroy'
        );
    }
}