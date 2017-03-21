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
         * URL Management
         */
        /* domain */
        'App\Events\UrlManagement\Domain\BeforeIndex' => [],
        'App\Events\UrlManagement\Domain\AfterIndex' => [],
        'App\Events\UrlManagement\Domain\BeforeShow' => [],
        'App\Events\UrlManagement\Domain\AfterShow' => [],
        'App\Events\UrlManagement\Domain\BeforeCreate' => [],
        'App\Events\UrlManagement\Domain\AfterCreate' => [],
        'App\Events\UrlManagement\Domain\BeforeStore' => [],
        'App\Events\UrlManagement\Domain\AfterStore' => [],
        'App\Events\UrlManagement\Domain\BeforeEdit' => [],
        'App\Events\UrlManagement\Domain\AfterEdit' => [],
        'App\Events\UrlManagement\Domain\BeforeUpdate' => [],
        'App\Events\UrlManagement\Domain\AfterUpdate' => [],
        'App\Events\UrlManagement\Domain\BeforeDestroy' => [],
        'App\Events\UrlManagement\Domain\AfterDestroy' => [],

        /* domain meta */
        'App\Events\UrlManagement\DomainMeta\BeforeIndex' => [],
        'App\Events\UrlManagement\DomainMeta\AfterIndex' => [],
        'App\Events\UrlManagement\DomainMeta\BeforeShow' => [],
        'App\Events\UrlManagement\DomainMeta\AfterShow' => [],
        'App\Events\UrlManagement\DomainMeta\BeforeCreate' => [],
        'App\Events\UrlManagement\DomainMeta\AfterCreate' => [],
        'App\Events\UrlManagement\DomainMeta\BeforeStore' => [],
        'App\Events\UrlManagement\DomainMeta\AfterStore' => [],
        'App\Events\UrlManagement\DomainMeta\BeforeEdit' => [],
        'App\Events\UrlManagement\DomainMeta\AfterEdit' => [],
        'App\Events\UrlManagement\DomainMeta\BeforeUpdate' => [],
        'App\Events\UrlManagement\DomainMeta\AfterUpdate' => [],
        'App\Events\UrlManagement\DomainMeta\BeforeDestroy' => [],
        'App\Events\UrlManagement\DomainMeta\AfterDestroy' => [],

        /* item */
        'App\Events\UrlManagement\Item\BeforeIndex' => [],
        'App\Events\UrlManagement\Item\AfterIndex' => [],
        'App\Events\UrlManagement\Item\BeforeShow' => [],
        'App\Events\UrlManagement\Item\AfterShow' => [],
        'App\Events\UrlManagement\Item\BeforeCreate' => [],
        'App\Events\UrlManagement\Item\AfterCreate' => [],
        'App\Events\UrlManagement\Item\BeforeStore' => [],
        'App\Events\UrlManagement\Item\AfterStore' => [],
        'App\Events\UrlManagement\Item\BeforeEdit' => [],
        'App\Events\UrlManagement\Item\AfterEdit' => [],
        'App\Events\UrlManagement\Item\BeforeUpdate' => [],
        'App\Events\UrlManagement\Item\AfterUpdate' => [],
        'App\Events\UrlManagement\Item\BeforeDestroy' => [],
        'App\Events\UrlManagement\Item\AfterDestroy' => [],

        /* item meta */
        'App\Events\UrlManagement\ItemMeta\BeforeIndex' => [],
        'App\Events\UrlManagement\ItemMeta\AfterIndex' => [],
        'App\Events\UrlManagement\ItemMeta\BeforeShow' => [],
        'App\Events\UrlManagement\ItemMeta\AfterShow' => [],
        'App\Events\UrlManagement\ItemMeta\BeforeCreate' => [],
        'App\Events\UrlManagement\ItemMeta\AfterCreate' => [],
        'App\Events\UrlManagement\ItemMeta\BeforeStore' => [],
        'App\Events\UrlManagement\ItemMeta\AfterStore' => [],
        'App\Events\UrlManagement\ItemMeta\BeforeEdit' => [],
        'App\Events\UrlManagement\ItemMeta\AfterEdit' => [],
        'App\Events\UrlManagement\ItemMeta\BeforeUpdate' => [],
        'App\Events\UrlManagement\ItemMeta\AfterUpdate' => [],
        'App\Events\UrlManagement\ItemMeta\BeforeDestroy' => [],
        'App\Events\UrlManagement\ItemMeta\AfterDestroy' => [],

        /* item meta conf */
        'App\Events\UrlManagement\ItemMetaConf\BeforeIndex' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterIndex' => [],
        'App\Events\UrlManagement\ItemMetaConf\BeforeShow' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterShow' => [],
        'App\Events\UrlManagement\ItemMetaConf\BeforeCreate' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterCreate' => [],
        'App\Events\UrlManagement\ItemMetaConf\BeforeStore' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterStore' => [],
        'App\Events\UrlManagement\ItemMetaConf\BeforeEdit' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterEdit' => [],
        'App\Events\UrlManagement\ItemMetaConf\BeforeUpdate' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterUpdate' => [],
        'App\Events\UrlManagement\ItemMetaConf\BeforeDestroy' => [],
        'App\Events\UrlManagement\ItemMetaConf\AfterDestroy' => [],

        /* url */
        'App\Events\UrlManagement\Url\BeforeIndex' => [],
        'App\Events\UrlManagement\Url\AfterIndex' => [],
        'App\Events\UrlManagement\Url\BeforeShow' => [],
        'App\Events\UrlManagement\Url\AfterShow' => [],
        'App\Events\UrlManagement\Url\BeforeCreate' => [],
        'App\Events\UrlManagement\Url\AfterCreate' => [],
        'App\Events\UrlManagement\Url\BeforeStore' => [],
        'App\Events\UrlManagement\Url\AfterStore' => [],
        'App\Events\UrlManagement\Url\BeforeEdit' => [],
        'App\Events\UrlManagement\Url\AfterEdit' => [],
        'App\Events\UrlManagement\Url\BeforeUpdate' => [],
        'App\Events\UrlManagement\Url\AfterUpdate' => [],
        'App\Events\UrlManagement\Url\BeforeDestroy' => [],
        'App\Events\UrlManagement\Url\AfterDestroy' => [],

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

        /**
         * Admin
         */
        /* user activity log */
        'App\Events\Admin\UserActivityLog\BeforeIndex' => [],
        'App\Events\Admin\UserActivityLog\AfterIndex' => [],
        'App\Events\Admin\UserActivityLog\BeforeShow' => [],
        'App\Events\Admin\UserActivityLog\AfterShow' => [],
        'App\Events\Admin\UserActivityLog\BeforeCreate' => [],
        'App\Events\Admin\UserActivityLog\AfterCreate' => [],
        'App\Events\Admin\UserActivityLog\BeforeStore' => [],
        'App\Events\Admin\UserActivityLog\AfterStore' => [],
        'App\Events\Admin\UserActivityLog\BeforeEdit' => [],
        'App\Events\Admin\UserActivityLog\AfterEdit' => [],
        'App\Events\Admin\UserActivityLog\BeforeUpdate' => [],
        'App\Events\Admin\UserActivityLog\AfterUpdate' => [],
        'App\Events\Admin\UserActivityLog\BeforeDestroy' => [],
        'App\Events\Admin\UserActivityLog\AfterDestroy' => [],
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

        /*url management*/
        'App\Listeners\UrlManagement\DomainControllerEventSubscriber',
        'App\Listeners\UrlManagement\DomainMetaControllerEventSubscriber',
        'App\Listeners\UrlManagement\ItemControllerEventSubscriber',
        'App\Listeners\UrlManagement\ItemMetaControllerEventSubscriber',
        'App\Listeners\UrlManagement\ItemMetaConfControllerEventSubscriber',
        'App\Listeners\UrlManagement\UrlControllerEventSubscriber',

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

        /**
         * Admin
         */
        'App\Listeners\Admin\UserActivityLogControllerEventSubscriber',
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
