<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/10/2017
 * Time: 5:04 PM
 */

namespace App\Listeners\Auth;


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

    }

    public function onAuthLogout($event)
    {

    }

    public function onAuthRegistered($event)
    {
        $user = $event->user;
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