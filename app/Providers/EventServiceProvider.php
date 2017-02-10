<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Product\Category\BeforeIndex' => [],
        'App\Events\Product\Category\AfterIndex' => [],
        'App\Events\Product\Category\BeforeShow' => [],
        'App\Events\Product\Category\AfterShow' => [],
        'App\Events\Product\Category\BeforeCreate' => [],
        'App\Events\Product\Category\AfterCreate' => [],
        'App\Events\Product\Category\BeforeStore' => [],
        'App\Events\Product\Category\AfterStore' => [],
        'App\Events\Product\Category\BeforeEdit' => [],
        'App\Events\Product\Category\AfterEdit' => [],
        'App\Events\Product\Category\BeforeUpdate' => [],
        'App\Events\Product\Category\AfterUpdate' => [],
        'App\Events\Product\Category\BeforeDestroy' => [],
        'App\Events\Product\Category\AfterDestroy' => [],

        'App\Events\Product\Product\BeforeIndex' => [],
        'App\Events\Product\Product\AfterIndex' => [],
        'App\Events\Product\Product\BeforeShow' => [],
        'App\Events\Product\Product\AfterShow' => [],
        'App\Events\Product\Product\BeforeCreate' => [],
        'App\Events\Product\Product\AfterCreate' => [],
        'App\Events\Product\Product\BeforeStore' => [],
        'App\Events\Product\Product\AfterStore' => [],
        'App\Events\Product\Product\BeforeEdit' => [],
        'App\Events\Product\Product\AfterEdit' => [],
        'App\Events\Product\Product\BeforeUpdate' => [],
        'App\Events\Product\Product\AfterUpdate' => [],
        'App\Events\Product\Product\BeforeDestroy' => [],
        'App\Events\Product\Product\AfterDestroy' => [],

        'App\Events\Product\Site\BeforeIndex' => [],
        'App\Events\Product\Site\AfterIndex' => [],
        'App\Events\Product\Site\BeforeShow' => [],
        'App\Events\Product\Site\AfterShow' => [],
        'App\Events\Product\Site\BeforeCreate' => [],
        'App\Events\Product\Site\AfterCreate' => [],
        'App\Events\Product\Site\BeforeStore' => [],
        'App\Events\Product\Site\AfterStore' => [],
        'App\Events\Product\Site\BeforeEdit' => [],
        'App\Events\Product\Site\AfterEdit' => [],
        'App\Events\Product\Site\BeforeUpdate' => [],
        'App\Events\Product\Site\AfterUpdate' => [],
        'App\Events\Product\Site\BeforeDestroy' => [],
        'App\Events\Product\Site\AfterDestroy' => [],
    ];

    protected $subscribe = [
//        /*subscription*/
//        'App\Listeners\Subscription\ProductControllerEventSubscriber',
//        'App\Listeners\Subscription\SubscriptionControllerEventSubscriber',
//        /*product*/
        'App\Listeners\Product\CategoryControllerEventSubscriber',
        'App\Listeners\Product\ProductControllerEventSubscriber',
        'App\Listeners\Product\SiteControllerEventSubscriber',
//        /*user*/
        'App\Listeners\Auth\AuthenticationEventSubscriber',
        'App\Listeners\Auth\ForgotPasswordControllerEventSubscriber',
        'App\Listeners\Auth\LoginControllerEventSubscriber',
        'App\Listeners\Auth\RegisterControllerEventSubscriber',
        'App\Listeners\Auth\ResetPasswordControllerEventSubscriber',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
