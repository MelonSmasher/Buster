<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth.apikey']], function () {
    Route::post('/bust', 'HomeController@bust')->name('api.bust');
    Route::get('/history/{id}', 'HomeController@history')->name('api.history');
});

