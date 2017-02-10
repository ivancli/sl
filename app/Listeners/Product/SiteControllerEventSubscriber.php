<?php
namespace App\Listeners\Product;
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
            'App\Events\Product\Site\Index\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeIndex'
        );
        $events->listen(
            'App\Events\Product\Site\Index\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterIndex'
        );

        $events->listen(
            'App\Events\Product\Site\Show\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeShow'
        );
        $events->listen(
            'App\Events\Product\Site\Show\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterShow'
        );

        $events->listen(
            'App\Events\Product\Site\Create\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeCreate'
        );
        $events->listen(
            'App\Events\Product\Site\Create\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterCreate'
        );

        $events->listen(
            'App\Events\Product\Site\Store\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeStore'
        );
        $events->listen(
            'App\Events\Product\Site\Store\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterStore'
        );

        $events->listen(
            'App\Events\Product\Site\Edit\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeEdit'
        );
        $events->listen(
            'App\Events\Product\Site\Edit\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterEdit'
        );

        $events->listen(
            'App\Events\Product\Site\Update\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeUpdate'
        );
        $events->listen(
            'App\Events\Product\Site\Update\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterUpdate'
        );

        $events->listen(
            'App\Events\Product\Site\Destroy\Before',
            'App\Listeners\Product\SiteControllerEventSubscriber@onBeforeDestroy'
        );
        $events->listen(
            'App\Events\Product\Site\Destroy\After',
            'App\Listeners\Product\SiteControllerEventSubscriber@onAfterDestroy'
        );
    }
}