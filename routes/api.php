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

////三山文化 微信项目路由需要验证openid
//Route::group([
//    'prefix' => 'sswh',
//    'middleware' => 'wxAuthV1.sswhCheckOpenid',
//    'namespace' => 'Sswh'
//], function () {
//    require __DIR__ . '/wechat/sswh.php';
//});
//三山文化 微信项目路由需要验证openid
Route::group(['prefix' => 'sswh', 'namespace' => 'Sswh'], function () {
    Route::group(['middleware' => 'wxAuthV1.sswhCheckOpenid'], function () {
        require __DIR__ . '/wechat/sswh.php'; //三山文化 微信项目路由   需要openid
    });
    require __DIR__ . '/wechat/sswh-NoAuth.php'; //三山文化 微信项目路由  不需要openid
//    require __DIR__ . '/admin/sswhapi.php'; //三山文化 微信项目后台列表 路由
});


//公共路由
Route::post('/common/upload','\App\Http\Controllers\Common\UploadController@uploadSinglePic'); //上传图片

//不需要验证openid
Route::group(['prefix' => 'noauth'], function () {
    require __DIR__ . '/wechat/sswh-NoAuth.php';
});