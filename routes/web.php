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

Route::get('/', function () {
    return view('welcome');
});

Route::get('alipay/test','TestController@alipay');

Route::get('/test/alipay/return','Alipay\PayController@aliReturn');
Route::post('/test/alipay/notify','Alipay\PayController@notify');

Route::prefix('api/')->middleware('ApiHeader')->group(function () {
    Route::post('/a', 'Api\ApiController@a');
});
Route::post('api/brush','Api\ApiController@brush');
Route::post('api/index', 'Api\ApiController@index');
Route::post('api/create', 'Api\ApiController@create');


Route::get('api/encryption', 'Api\ApiController@encryption');
Route::get('api/decrypt', 'Api\ApiController@decrypt');

