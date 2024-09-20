<?php

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

Route::group(['middleware' => 'interview'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::post('getpresettokenimages', 'ApiController@getpresettokenimages');
        Route::post('savetoken', 'ApiController@savetoken');
        Route::post('gettokens', 'ApiController@getTokens');
        Route::post('deletetoken', 'ApiController@deleteToken');
    });
});
