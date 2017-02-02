<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/2/2017
 * Time: 12:21 PM
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Repositories\Subscription\ProductContract', 'App\Repositories\Subscription\ProductRepository');
        $this->app->bind('App\Contracts\Repositories\Subscription\ProductFamilyContract', 'App\Repositories\Subscription\ProductFamilyRepository');
        $this->app->bind('App\Contracts\Repositories\Subscription\SubscriptionContract', 'App\Repositories\Subscription\SubscriptionRepository');
        $this->app->bind('App\Contracts\Repositories\Subscription\SubscriptionManagementContract', 'App\Repositories\Subscription\SubscriptionManagementRepository');
    }
}