<?php

namespace App\Providers;

use App\Models\Domain;
use App\Models\Subscription;
use App\Models\Url;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
            $user->setPreference('DATE_FORMAT', 'Y-m-d');
            $user->setPreference('TIME_FORMAT', 'g:i:a');
        });

        Url::created(function ($url) {
            /* create new domain if it's not yet in DB */
            if (Domain::where($url->domainFullPath)->count() == 0) {
                new Domain([
                    'full_path' => $url->domainFullPath
                ]);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Repositories\Product\CategoryContract', 'App\Repositories\Product\CategoryRepository');
        $this->app->bind('App\Contracts\Repositories\Product\ProductContract', 'App\Repositories\Product\ProductRepository');
        $this->app->bind('App\Contracts\Repositories\Product\SiteContract', 'App\Repositories\Product\SiteRepository');
        $this->app->bind('App\Contracts\Repositories\Product\UrlContract', 'App\Repositories\Product\UrlRepository');

        $this->app->bind('App\Contracts\Repositories\UserManagement\UserContract', 'App\Repositories\UserManagement\UserRepository');
        $this->app->bind('App\Contracts\Repositories\UserManagement\GroupContract', 'App\Repositories\UserManagement\GroupRepository');
        $this->app->bind('App\Contracts\Repositories\UserManagement\RoleContract', 'App\Repositories\UserManagement\RoleRepository');
        $this->app->bind('App\Contracts\Repositories\UserManagement\PermissionContract', 'App\Repositories\UserManagement\PermissionRepository');

    }
}
