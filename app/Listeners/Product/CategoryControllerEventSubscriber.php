<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/10/2017
 * Time: 5:00 PM
 */

namespace App\Listeners\Product;


class CategoryControllerEventSubscriber
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
            'App\Events\Product\Category\Index\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Product\Category\Index\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Product\Category\Show\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Product\Category\Show\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Product\Category\Create\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Product\Category\Create\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Product\Category\Store\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Product\Category\Store\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Product\Category\Edit\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Product\Category\Edit\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Product\Category\Update\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Product\Category\Update\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Product\Category\Destroy\Before',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Product\Category\Destroy\After',
            'App\Listeners\Product\CategoryControllerEventSubscriber@onAfterDestroy'
        );
    }
}