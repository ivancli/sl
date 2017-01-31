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
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register.get');
Route::get('forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot.get');