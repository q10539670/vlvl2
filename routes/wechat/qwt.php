<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/29
 * Time: 16:29
 */

//Route::any('/test','L190429Controller@test');
Route::any('/test2','L190603Controller@test2');
//Route::get('foo', function () {
//    return 'Hello World';
//});

/**
 * 电信9月份抽奖 190925
 */
Route::post('/dx190925/init','Dx190925Controller@appInitHandler');   //初始化程序
Route::get('/dx190925/user','Dx190925Controller@user');   //获取/记录用户信息
Route::post('/dx190925/area','Dx190925Controller@setArea');   //提交区域信息
Route::post('/dx190925/location','Dx190925Controller@setLocation');   //提交经纬度
Route::post('/dx190925/flop','Dx190925Controller@flop');   //抽卡
Route::post('/dx190925/prize','Dx190925Controller@randomPrize');   //抽奖
Route::get('/dx190925/myprize','Dx190925Controller@myPrize');   //查询我的奖品
Route::post('/dx190925/phone','Dx190925Controller@phone');   //提交手机号

/**
 * 电信10月份抽奖 191022
 */
Route::post('/x191022/init','X191022Controller@appInitHandler');   //初始化程序
Route::get('/x191022/user','X191022Controller@userInfo');   //获取/记录用户信息
Route::post('/x191022/area','X191022Controller@setArea');   //提交区域信息
Route::post('/x191022/location','X191022Controller@setLocation');   //提交经纬度
Route::post('/x191022/score','X191022Controller@score');   //提交成绩
Route::post('/x191022/prize','X191022Controller@randomPrize');   //抽奖
Route::post('/x191022/share','X191022Controller@share');   //分享

/**
 * 电信11月份抽奖 191119
 */
Route::post('/x191119/init','X191119Controller@appInitHandler');   //初始化程序
Route::get('/x191119/user','X191119Controller@userInfo');   //获取/记录用户信息
Route::post('/x191119/area','X191119Controller@setArea');   //提交区域信息
Route::post('/x191119/location','X191119Controller@setLocation');   //提交经纬度
Route::post('/x191119/prize','X191119Controller@randomPrize');   //抽奖

/**
 * 电信12月份抽奖 191212
 */
Route::post('/x191212/init',    'X191212Controller@appInitHandler');   //初始化程序
Route::get( '/x191212/user',    'X191212Controller@userInfo');   //获取/记录用户信息
Route::post('/x191212/area',    'X191212Controller@setArea');   //提交区域信息
Route::post('/x191212/location','X191212Controller@setLocation');   //提交经纬度
Route::post('/x191212/prize',   'X191212Controller@randomPrize');   //抽奖
Route::post('/x191212/post',    'X191212Controller@post');   //提交信息
