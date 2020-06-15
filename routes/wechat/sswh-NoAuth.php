<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/29
 * Time: 16:29
 */


/*
 * 美年达 20190604
 *
 * */
Route::post('/Lx190604mldAdmin/postList','Lx190604mldAdminController@postList');   //用户提交信息列表
Route::post('/Lx190604mldAdmin/messageList','Lx190604mldAdminController@messageList');   //用户反馈列表



/**
 * 宜昌中心·天宸府 200106  台州商会
 */
Route::get('/x200106/index',  '\App\Http\Controllers\Admin\Sswh\X200106Controller@index');
Route::get('/x200106/export', '\App\Http\Controllers\Admin\Sswh\X200106Controller@export');
Route::get('/x200106r/round', '\App\Http\Controllers\Admin\Sswh\X200106Controller@round');
Route::post('/x200106r/set_round', '\App\Http\Controllers\Admin\Sswh\X200106Controller@setRound');
Route::get('/x200106r/get_prize/{round}', '\App\Http\Controllers\Admin\Sswh\X200106Controller@getPrizeNum');
Route::post('/x200106r/prizes', '\App\Http\Controllers\Admin\Sswh\X200106Controller@getPrize');              //抽奖
Route::get('/x200106r/get_round/', '\App\Http\Controllers\Admin\Sswh\X200106Controller@get_round');              //抽奖
