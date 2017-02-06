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
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login.get');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register.get');
Route::post('register', 'Auth\RegisterController@register')->name('register.post');
Route::get('forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot.get');
Route::post('forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

Route::get('subscription/product', 'Subscription\ProductController@index')->name('subscription.product.index');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {

    })->name('home.get');
});