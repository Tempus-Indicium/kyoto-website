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

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::group(['namespace' => 'Api'], function () {
    Route::get('/', function ()    {
        return "test of the api routes";
    });

    // TODO: change to post
    Route::get('last-five-seconds/{stationId}', [
         'name' => 'last-five-seconds',
         'uses' => 'ApiController@lastFiveSeconds',
    ]);
});
