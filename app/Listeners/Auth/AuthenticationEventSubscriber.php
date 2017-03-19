<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/10/2017
 * Time: 5:04 PM
 */

namespace App\Listeners\Auth;


use App\Jobs\Log\UserActivity;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class AuthenticationEventSubscriber
{

    public function onAuthAttempting($event)
    {

    }

    public function onAuthAuthenticated($event)
    {

    }

    public function onAuthFailed($event)
    {

    }

    public function onAuthLockout($event)
    {

    }

    public function onAuthLogin($event)
    {
        $user = $event->user;

        $activity = "Signed In";
        dispatch((new UserActivity($user, $activity))->onQueue("log"));

        if (!is_null($user->subscription)) {
            Cache::forget("chargify.subscriptions.{$user->subscription->api_subscription_id}");
        }
    }

    public function onAuthLogout($event)
    {
        $user = $event->user;

        $activity = "Signed Out";
        dispatch((new UserActivity($user, $activity))->onQueue("log"));

    }

    public function onAuthRegistered($event)
    {

        $user = $event->user;

        $activity = "Signed Up";
        dispatch((new UserActivity($user, $activity))->onQueue("log"));

        /* assign registered users to client role */
        $client = Role::where('name', 'client')->first();
        $user->attachRole($client);
    }

    /**
     * register events for the subscriber
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Attempting',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthAttempting'
        );

        $events->listen(
            'Illuminate\Auth\Events\Authenticated',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthAuthenticated'
        );

        $events->listen(
            'Illuminate\Auth\Events\Failed',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthFailed'
        );

        $events->listen(
            'Illuminate\Auth\Events\Lockout',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthLockout'
        );

        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthLogout'
        );

        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\Listeners\Auth\AuthenticationEventSubscriber@onAuthRegistered'
        );
    }
}