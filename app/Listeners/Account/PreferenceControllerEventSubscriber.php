<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/23/2017
 * Time: 4:32 PM
 */

namespace App\Listeners\Account;


use App\Jobs\Log\UserActivity;

class PreferenceControllerEventSubscriber
{
    public function onBeforeUpdate()
    {

    }

    public function onAfterUpdate()
    {
        $activity = "Updated Account Preferences";
        dispatch((new UserActivity(auth()->user(), $activity))->onQueue("log"));
    }


    /**
     * register events for the subscriber
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Account\Preference\BeforeUpdate',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onBeforeUpdate'
        );

        $events->listen(
            'App\Events\Account\Preference\AfterUpdate',
            'App\Listeners\Account\ProfileControllerEventSubscriber@onAfterUpdate'
        );
    }
}