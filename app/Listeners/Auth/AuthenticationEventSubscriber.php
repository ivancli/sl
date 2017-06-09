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
use App\Models\User;
use App\Services\MailingAgent\CampaignMonitor\MailingAgentService;
use Carbon\Carbon;
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
        /*TODO log IP and requested email address*/
    }

    public function onAuthLogin($event)
    {
        $user = $event->user;

        $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        if (!is_null($user->subscription)) {
            $apiDomain = config("chargify.{$user->subscription->location}.api_domain");

            Cache::forget("{$apiDomain}.chargify.subscriptions.{$user->subscription->api_subscription_id}");
        }

        $activity = "User -- {$user->fullName} -- Signed In";
        $this->dispatchUserActivityLog($activity, $user);

    }

    public function onAuthLogout($event)
    {
        $user = $event->user;
        if (isset($user) && !is_null($user)) {
            $activity = "User -- {$user->fullName} -- Logged Out";
            $this->dispatchUserActivityLog($activity, $user);
        }
    }

    public function onAuthRegistered($event)
    {
        $user = $event->user;

        /* assign registered users to client role */
        $client = Role::where('name', 'client')->first();
        $user->attachRole($client);

        $activity = "User -- {$user->fullName} -- Signed Up";
        $this->dispatchUserActivityLog($activity, $user);
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

    protected function dispatchUserActivityLog($activity, User $user = null)
    {
        if (is_null($user) && auth()->check()) {
            $user = auth()->user();
        } else {
            return false;
        }
        dispatch((new UserActivity($user, $activity))->onQueue("log")->onConnection('sync'));
    }
}