<?php
namespace App\Listeners\Account;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 1:23 PM
 */
class AccountSettingsControllerEventSubscriber
{
    public function onBeforeIndex()
    {

    }

    public function onAfterIndex()
    {

    }


    /**
     * register events for the subscriber
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Account\AccountSettings\BeforeIndex',
            'App\Listeners\Account\AccountSettingsControllerEventSubscriber@onBeforeIndex'
        );

        $events->listen(
            'App\Events\Account\AccountSettings\AfterIndex',
            'App\Listeners\Account\AccountSettingsControllerEventSubscriber@onAfterIndex'
        );
    }
}