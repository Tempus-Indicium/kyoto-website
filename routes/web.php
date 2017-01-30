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

Route::get('/', function () {
    return view('welcome');
});

Route::get('voorbeeld', 'ExampleController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('map', 'map@stationsJapan')->name('map');

Route::get('/stations', 'StationController@index')->name('stations');

Route::get('station_information/{stn}', 'station_information@page');

Route::get('/help', 'HelpController@index')->name('help');

