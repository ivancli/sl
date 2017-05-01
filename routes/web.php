<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#region Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login.get');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register.get');
Route::post('register', 'Auth\RegisterController@register')->name('register.post');
Route::get('forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot.get');
Route::post('forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
#endregion

#region Subscription Routes
Route::get('subscription/product', 'Subscription\ProductController@index')->name('subscription.product.index');
Route::resource('subscription/subscription', 'Subscription\SubscriptionController');
#endregion


Route::group(['middleware' => ['auth', 'subs']], function () {
    Route::get('/', function () {
        return view('app.product.index');
    })->name('home.get');

    #region User Account Client Routes
    Route::resource('account_settings', 'Account\AccountSettingsController');
    Route::resource('user/profile', 'Account\ProfileController');
    Route::resource('user/preference', 'Account\PreferenceController');
    #endregion

    #region Product Related Routes
    Route::resource('category', 'Product\CategoryController', ['except' => [
        'create'
    ]]);

    Route::resource('product', 'Product\ProductController', ['except' => [
        'create'
    ]]);

    Route::resource('site', 'Product\SiteController', ['except' => [
        'create'
    ]]);
    Route::put('site/item/{site}', 'Product\SiteController@assignItem')->name('site.item.update');

    #endregion

    #region Product Side Features Related Routes
    Route::resource('alert', 'Alert\AlertController');
    Route::resource('report', 'Report\ReportController');
    #endregion

    #region Administration Routes
    Route::resource('app-preference', 'Admin\AppPrefController');

    Route::resource('log/user-activity-log', 'Admin\UserActivityLogController');
    #endregion

    #region URL Management
    Route::group(['prefix' => 'url-management'], function () {
        Route::resource('domain', 'UrlManagement\DomainController');
        Route::resource('domain-meta', 'UrlManagement\DomainMetaController', [
            'parameters' => [
                'domain-meta' => 'domainMeta'
            ]
        ]);
        #region Domain Conf
        Route::resource('domain-conf', 'UrlManagement\DomainConfController');
        #endregion

        #region URL
        Route::resource('url', 'UrlManagement\UrlController');
        Route::post('url/queue/{url}', 'UrlManagement\UrlController@queue')->name('url.queue');
        Route::post('url/assign/{url}', 'UrlManagement\UrlController@assign')->name('url.assign');
        #endregion

        #region Item
        Route::resource('item', 'UrlManagement\ItemController');
        Route::post('item/queue/{item}', 'UrlManagement\ItemController@queue')->name('item.queue');
        #endregion

        #region ItemMeta
        Route::resource('item-meta', 'UrlManagement\ItemMetaController', [
            'parameters' => [
                'item-meta' => 'itemMeta'
            ]
        ]);
        Route::post('item-meta/queue/{itemMeta}', 'UrlManagement\ItemMetaController@queue')->name('item-meta.queue');
        #endregion

        Route::resource('item-meta-conf', 'UrlManagement\ItemMetaConfController');

        #region Test Crawl Parse
        Route::group(['prefix' => 'test'], function () {
            Route::post('crawl-parse-item-meta/{itemMeta}', 'UrlManagement\TestController@crawlParseItemMeta')->name('item-meta.test');
            Route::post('crawl-parse-item/{item}', 'UrlManagement\TestController@crawlParseItem')->name('item.test');
            Route::post('crawl-parse-url/url/{url}', 'UrlManagement\TestController@crawlParseUrl')->name('url.test');
        });
        #endregion
    });
    #endregion

    #region User Management
    Route::group(['prefix' => 'user-management'], function () {
        Route::resource('user', 'UserManagement\UserController');
        Route::resource('group', 'UserManagement\GroupController');
        Route::resource('role', 'UserManagement\RoleController');
        Route::resource('permission', 'UserManagement\PermissionController');
    });
    #endregion
});

#region Error Handling Routes
/*
|--------------------------------------------------------------------------
| Errors Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'errors'], function () {
    Route::get('javascript-disabled', function () {
        return view('errors.javascript_disabled');
    })->name('errors.javascript_disabled');
    Route::get('cookie-disabled', function () {
        return view('errors.cookie_disabled');
    })->name('errors.cookie_disabled');
});
#endregion

#region Internal Testing/Debug Route
Route::any('test', 'TestController@test');
#endregion