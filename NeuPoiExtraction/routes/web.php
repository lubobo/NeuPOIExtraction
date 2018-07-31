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

Route::get('/', 'Home\HomeController@getWelcome')->name('getWelcome');

Route::post('/postFile', 'Home\HomeController@postFile')->name('postFile');

Route::get('/getFile', 'Home\HomeController@getFile')->name('getFile');

Route::post('/downloadFile', 'Home\HomeController@downloadFile')->name('downloadFile');

Route::post('/cleanData', 'Home\HomeController@cleanData')->name('cleanData');

Route::post('/downloadSyFile', 'Home\HomeController@downloadSyFile')->name('downloadSyFile');

Route::post('/cleanTaxiData', 'Home\HomeController@cleanTaxiData')->name('cleanTaxiData');

Route::post('/KMeansData', 'Home\HomeController@KMeansData')->name('KMeansData');

Route::post('/getBasePoiData', 'Home\HomeController@getBasePoiData')->name('getBasePoiData');

Route::post('/getTestPoiData', 'Home\HomeController@getTestPoiData')->name('getTestPoiData');

Route::post('/getPoiIdData', 'Home\HomeController@getPoiIdData')->name('getPoiIdData');

Route::post('getPoiData', 'Home\HomeController@getPoiData')->name('getPoiData');

Route::get('/resetSystem', 'Home\HomeController@resetSystem')->name('resetSystem');

