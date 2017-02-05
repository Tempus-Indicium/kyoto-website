<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => 'auth'], function() {

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/help', 'HelpController@index')->name('help');

    Route::get('/map', 'map@stationsJapan')->name('map');

    Route::get('/stations', 'StationController@index')->name('stations');

    Route::get('/station_information/{stn}', 'station_information@page')->name('stninfo');

    Route::get('/ajax/{stn}', 'station_information@ajax');
});

Auth::routes();
