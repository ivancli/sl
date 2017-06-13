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
Route::get('external-register', 'Auth\RegisterController@externalRegister')->name('external-register.get');
Route::get('forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot.get');
Route::post('forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot.post');
Route::get('password', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
#endregion

#region Token Routes
Route::group(['prefix' => 'api'], function () {
    Route::get('token', 'API\TokenController@get')->name('api.token.get');
    Route::get('token/verify', 'API\TokenController@verify')->name('api.token.verify');

    Route::get('geo/country/{ip_address?}', 'API\GeoController@country')->name('api.geo.country');
    Route::get('geo/state/{ip_address?}', 'API\GeoController@state')->name('api.geo.state');
    Route::get('geo/city/{ip_address?}', 'API\GeoController@city')->name('api.geo.city');
    Route::get('geo/{ip_address?}', 'API\GeoController@all')->name('api.geo.all');
});

#region Subscription Routes
Route::get('subscription/coupon', 'Subscription\CouponController@verify')->name('subscription.coupon.verify');
Route::get('subscription/product', 'Subscription\ProductController@index')->name('subscription.product.index');
Route::get('subscription/update', 'Subscription\SubscriptionController@updated')->name('subscription.updated');
Route::post('subscription/reactivate/{subscription}', 'Subscription\SubscriptionController@reactivate')->name('subscription.reactivate');
Route::resource('subscription/subscription', 'Subscription\SubscriptionController');
#endregion


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'Dashboard\WidgetController@index')->name('home.get');

    #region User Account Client Routes
    Route::resource('account-settings', 'Account\AccountSettingsController');
    Route::resource('user/profile', 'Account\ProfileController');
    Route::put('user/profile/password/{user_id}', 'Account\ProfileController@password')->name('profile.password');
    Route::put('user/profile/tour/{user_id}', 'Account\ProfileController@tour')->name('profile.tour');
    Route::resource('user/preference', 'Account\PreferenceController');
    Route::resource('user/user-domain', 'Account\UserDomainController');
    Route::get('user/sample-account', 'Account\SampleAccountController@index')->name('sample-account.index');
    Route::post('user/sample-account', 'Account\SampleAccountController@store')->name('sample-account.store');
    #endregion

    #region Dashboard Related Routes
    Route::resource('dashboard/widget', 'Dashboard\WidgetController');
    #endregion

    #region Product Related Routes
    Route::resource('category', 'Product\CategoryController', ['except' => [
        'create'
    ]]);
    Route::get('category/report/{category}', 'Product\CategoryController@report')->name('category.report.show');

    Route::resource('product', 'Product\ProductController', ['except' => [
        'create'
    ]]);
    Route::get('product/report/{product}', 'Product\ProductController@report')->name('product.report.show');

    Route::get('bulk-import', 'Product\BulkImportController@index')->name('bulk-import.index');
    Route::post('bulk-import', 'Product\BulkImportController@store')->name('bulk-import.store');


    Route::resource('site', 'Product\SiteController', ['except' => [
        'create'
    ]]);
    Route::put('site/item/{site}', 'Product\SiteController@assignItem')->name('site.item.update');

    Route::get('chart/site', 'Chart\ChartController@sitePrice')->name('chart.site.price');
    Route::get('chart/product', 'Chart\ChartController@productPrice')->name('chart.product.price');
    Route::get('chart/category', 'Chart\ChartController@categoryPrice')->name('chart.category.price');

    #endregion

    #region Product Side Features Related Routes
    Route::resource('alert', 'Alert\AlertController');
    Route::resource('historical-alert', 'Alert\HistoricalAlertController');
    Route::resource('report', 'Report\ReportController');
    Route::resource('historical-report', 'Report\HistoricalReportController');
    Route::get('positioning/filter', 'Report\PositioningController@filter')->name('positioning.filter');
    Route::get('positioning/export', 'Report\PositioningController@export')->name('positioning.export');
    Route::resource('positioning', 'Report\PositioningController');
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
        Route::get('item/price/{item}', 'UrlManagement\ItemController@prices')->name('item.price');
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
        Route::get('user/login-as/{user}', 'UserManagement\UserController@loginAs')->name('user.login-as');
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