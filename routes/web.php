<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 例如： Route::get('/test/cc','*') //地址  http://local_wx.sanshanwenhua.com/vlvl/test/cc
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/example/testWeb','\App\Http\Controllers\Example\TestController@testWeb');
Route::get('/example/test','\App\Http\Controllers\Example\TestController@test');


//
////后台简单页面 190604
//
Route::get('/sswhAdmin/login','\App\Http\Controllers\Sswh\SswhAdmin\LoginController@index');