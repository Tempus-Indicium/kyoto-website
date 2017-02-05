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

Route::group(['namespace' => 'Api', 'name' => 'api'], function () {
    Route::get('/', function ()    {
        return "test of the api routes";
    });

    Route::post('last-five-seconds/{stationId}', [
         'name' => 'last-five-seconds',
         'uses' => 'ApiController@lastFiveSeconds',
    ]);

    Route::post('top-ten-asia/{dateString}', [
         'name' => 'top-ten-asia',
         'uses' => 'ApiController@topTenAsia',
    ]);
});
