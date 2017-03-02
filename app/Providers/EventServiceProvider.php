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
        /**
         * account settings
         */
        'App\Events\Account\AccountSettings\BeforeIndex' => [],
        'App\Events\Account\AccountSettings\AfterIndex' => [],

        'App\Events\Account\Profile\BeforeShow' => [],
        'App\Events\Account\Profile\AfterShow' => [],
        'App\Events\Account\Profile\BeforeUpdate' => [],
        'App\Events\Account\Profile\AfterUpdate' => [],

        'App\Events\Account\Preference\BeforeUpdate' => [],
        'App\Events\Account\Preference\AfterUpdate' => [],

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

        /**
         * User management
         */
        /* user */
        'App\Events\UserManagement\User\BeforeIndex' => [],
        'App\Events\UserManagement\User\AfterIndex' => [],
        'App\Events\UserManagement\User\BeforeShow' => [],
        'App\Events\UserManagement\User\AfterShow' => [],
        'App\Events\UserManagement\User\BeforeCreate' => [],
        'App\Events\UserManagement\User\AfterCreate' => [],
        'App\Events\UserManagement\User\BeforeStore' => [],
        'App\Events\UserManagement\User\AfterStore' => [],
        'App\Events\UserManagement\User\BeforeEdit' => [],
        'App\Events\UserManagement\User\AfterEdit' => [],
        'App\Events\UserManagement\User\BeforeUpdate' => [],
        'App\Events\UserManagement\User\AfterUpdate' => [],
        'App\Events\UserManagement\User\BeforeDestroy' => [],
        'App\Events\UserManagement\User\AfterDestroy' => [],

        /* group */
        'App\Events\UserManagement\Group\BeforeIndex' => [],
        'App\Events\UserManagement\Group\AfterIndex' => [],
        'App\Events\UserManagement\Group\BeforeShow' => [],
        'App\Events\UserManagement\Group\AfterShow' => [],
        'App\Events\UserManagement\Group\BeforeCreate' => [],
        'App\Events\UserManagement\Group\AfterCreate' => [],
        'App\Events\UserManagement\Group\BeforeStore' => [],
        'App\Events\UserManagement\Group\AfterStore' => [],
        'App\Events\UserManagement\Group\BeforeEdit' => [],
        'App\Events\UserManagement\Group\AfterEdit' => [],
        'App\Events\UserManagement\Group\BeforeUpdate' => [],
        'App\Events\UserManagement\Group\AfterUpdate' => [],
        'App\Events\UserManagement\Group\BeforeDestroy' => [],
        'App\Events\UserManagement\Group\AfterDestroy' => [],

        /* role */
        'App\Events\UserManagement\Role\BeforeIndex' => [],
        'App\Events\UserManagement\Role\AfterIndex' => [],
        'App\Events\UserManagement\Role\BeforeShow' => [],
        'App\Events\UserManagement\Role\AfterShow' => [],
        'App\Events\UserManagement\Role\BeforeCreate' => [],
        'App\Events\UserManagement\Role\AfterCreate' => [],
        'App\Events\UserManagement\Role\BeforeStore' => [],
        'App\Events\UserManagement\Role\AfterStore' => [],
        'App\Events\UserManagement\Role\BeforeEdit' => [],
        'App\Events\UserManagement\Role\AfterEdit' => [],
        'App\Events\UserManagement\Role\BeforeUpdate' => [],
        'App\Events\UserManagement\Role\AfterUpdate' => [],
        'App\Events\UserManagement\Role\BeforeDestroy' => [],
        'App\Events\UserManagement\Role\AfterDestroy' => [],

        /* permission */
        'App\Events\UserManagement\Permission\BeforeIndex' => [],
        'App\Events\UserManagement\Permission\AfterIndex' => [],
        'App\Events\UserManagement\Permission\BeforeShow' => [],
        'App\Events\UserManagement\Permission\AfterShow' => [],
        'App\Events\UserManagement\Permission\BeforeCreate' => [],
        'App\Events\UserManagement\Permission\AfterCreate' => [],
        'App\Events\UserManagement\Permission\BeforeStore' => [],
        'App\Events\UserManagement\Permission\AfterStore' => [],
        'App\Events\UserManagement\Permission\BeforeEdit' => [],
        'App\Events\UserManagement\Permission\AfterEdit' => [],
        'App\Events\UserManagement\Permission\BeforeUpdate' => [],
        'App\Events\UserManagement\Permission\AfterUpdate' => [],
        'App\Events\UserManagement\Permission\BeforeDestroy' => [],
        'App\Events\UserManagement\Permission\AfterDestroy' => [],
    ];

    protected $subscribe = [
        /*todo this part is yet to be finished*/
//        /*subscription*/
//        'App\Listeners\Subscription\ProductControllerEventSubscriber',
//        'App\Listeners\Subscription\SubscriptionControllerEventSubscriber',

        /*account*/
        'App\Listeners\Account\AccountSettingsControllerEventSubscriber',
        'App\Listeners\Account\ProfileControllerEventSubscriber',
        'App\Listeners\Account\PreferenceControllerEventSubscriber',

        /*product*/
        'App\Listeners\Product\CategoryControllerEventSubscriber',
        'App\Listeners\Product\ProductControllerEventSubscriber',
        'App\Listeners\Product\SiteControllerEventSubscriber',

        /*auth*/
        'App\Listeners\Auth\AuthenticationEventSubscriber',
        'App\Listeners\Auth\ForgotPasswordControllerEventSubscriber',
        'App\Listeners\Auth\LoginControllerEventSubscriber',
        'App\Listeners\Auth\RegisterControllerEventSubscriber',
        'App\Listeners\Auth\ResetPasswordControllerEventSubscriber',

        /**
         * User management
         */
        /* user */
        'App\Listeners\UserManagement\UserControllerEventSubscriber',
        /* group */
        'App\Listeners\UserManagement\GroupControllerEventSubscriber',
        /* role */
        'App\Listeners\UserManagement\RoleControllerEventSubscriber',
        /* permission */
        'App\Listeners\UserManagement\PermissionControllerEventSubscriber',
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
