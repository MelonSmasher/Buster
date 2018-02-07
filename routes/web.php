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

Route::get('/', 'HomeController@root')->name('root');

// Authentication Routes...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset ');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

// App Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::get('/servers', 'HomeController@servers')->name('servers');
    Route::get('/proxies', 'HomeController@proxies')->name('proxies');
    Route::get('/uris', 'HomeController@uris')->name('uris');
    Route::get('/schemes', 'HomeController@schemes')->name('schemes');
    Route::get('/scheme/{id}', 'HomeController@scheme')->name('scheme');
    Route::post('/bust', 'HomeController@bust')->name('bust');

    Route::group(['prefix' => 'new'], function () {
        Route::get('/server', 'HomeController@newServer')->name('new.server');
        Route::get('/proxy', 'HomeController@newProxy')->name('new.proxy');
        Route::get('/uri', 'HomeController@newUri')->name('new.uri');
        Route::get('/scheme', 'HomeController@newScheme')->name('new.scheme');

        Route::post('/server', 'HomeController@createServer')->name('create.server');
        Route::post('/proxy', 'HomeController@createProxy')->name('create.proxy');
        Route::post('/uri', 'HomeController@createUri')->name('create.uri');
        Route::post('/scheme', 'HomeController@createScheme')->name('create.scheme');
    });

    Route::group(['prefix' => 'delete'], function () {
        Route::get('/server/{id}', 'HomeController@deleteServer')->name('delete.server');
        Route::get('/proxy/{id}', 'HomeController@deleteProxy')->name('delete.proxy');
        Route::get('/uri/{id}', 'HomeController@deleteUri')->name('delete.uri');
        Route::get('/scheme/{id}', 'HomeController@deleteScheme')->name('delete.scheme');
    });
});
