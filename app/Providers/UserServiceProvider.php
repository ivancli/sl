<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/8/2017
 * Time: 9:55 AM
 */

namespace App\Providers;


use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function ($user) {
            /* create blank subscription record */
            $user->subscription()->save(new Subscription);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Repositories\User\UserContract', 'App\Repositories\User\UserRepository');
    }
}
