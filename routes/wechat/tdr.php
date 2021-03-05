<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/3 0003
 * Time: 15:33
 */

/*获取redis存储的分享数据
 * */
//Route::get('system/share', '\App\Http\Controllers\Sswh\Common\ShareController@share'); //获取redis存储的分享数据

/*
* 180816md项目
* */
//Route::get('l180816md/index', 'L180816mdController@index');

/*汤达人(武汉)18年12月红包项目*/
Route::group(['namespace'=>'L181210'], function(){
    Route::get('l181210a/userInfo', 'ApiV1Controller@userInfo');  //获取用户信息
    Route::post('l181210a/upload', 'ApiV1Controller@uploadImg');  //报名
    Route::post('l181210a/upload2', 'ApiV1Controller@uploadImg2');  //报名
    Route::post('l181210a/upload3', 'ApiV1Controller@uploadImg3');  //报名
    Route::post('l181210a/isUploaded', 'ApiV1Controller@isUploaded');  //检测用户是否上传
    Route::any('l181210a/test', 'ApiV1Controller@test');  //报名
});


Route::group(['namespace'=>'L191127'], function(){
    Route::get('l191127/user', '\App\Http\Controllers\Ticket\L191127\ApiController@userInfo');  //获取用户信息
    Route::post('l191127/location', '\App\Http\Controllers\Ticket\L191127\ApiController@location');  //提交经纬度
    Route::post('l191127/upload', '\App\Http\Controllers\Ticket\L191127\ApiController@uploadImg');  //报名
    Route::any('l191127/test', '\App\Http\Controllers\Ticket\L191127\ApiController@test');  //测试接口
    Route::any('l191127/publish', '\App\Http\Controllers\Ticket\L191127\ApiController@publish');  //测试接口
    Route::get('l191127/users',  '\App\Http\Controllers\Ticket\L191127\ApiController@users');   //20条消息
    Route::get('l191127/time',  '\App\Http\Controllers\Ticket\L191127\ApiController@time');   //活动时间
    Route::get('l191127/prize_list',  '\App\Http\Controllers\Ticket\L191127\ApiController@prizeLists');   //奖品展示
    Route::get('l191127/prize_list2',  '\App\Http\Controllers\Ticket\L191127\ApiController@prizeListsForAct2');   //奖品展示
    Route::post('l191127/post',  '\App\Http\Controllers\Ticket\L191127\ApiController@post');   //奖品展示
});
