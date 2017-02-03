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
        'App\Events\Subscription\ProductController\MethodIndex' => [
            'App\Listeners\Subscription\ProductControllerEventListener',
        ],
        'App\Events\Subscription\SubscriptionController\MethodStore' => [
            'App\Listeners\Subscription\SubscriptionControllerEventListener',
        ],
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
