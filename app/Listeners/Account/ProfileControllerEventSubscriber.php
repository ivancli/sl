<?php
namespace App\Listeners\Account;
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 1:23 PM
 */
class ProfileControllerEventSubscriber
{
    public function onBeforeUpdate()
    {

    }

    public function onAfterUpdate()
    {

    }


    /**
     * register events for the subscriber
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Account\Profile\BeforeUpdate',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onBeforeUpdate'
        );

        $events->listen(
            'App\Events\Account\Profile\AfterUpdate',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onAfterUpdate'
        );
    }
}