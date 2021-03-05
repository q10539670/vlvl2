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
Route::get('/example/testWeb', '\App\Http\Controllers\Example\TestController@testWeb');
Route::get('/example/test', '\App\Http\Controllers\Example\TestController@test');


Route::group(['namespace' => 'Admin\Sswh'], function () {
    require __DIR__ . '/admin/sswh.php'; //三山文化 微信项目后台路由
});

Route::group(['namespace' => 'Admin\Qwt'], function () {
    require __DIR__ . '/admin/qwt.php'; //全网通 微信项目后台路由
});

Route::group(['namespace' => 'Admin\Jctj'], function () {
    require __DIR__ . '/admin/jctj.php'; //武汉江宸天街 微信项目后台路由
});

require __DIR__ . '/admin/ticket.php';  //汤达人
