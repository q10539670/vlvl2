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


/*
 * 美年达 20190604
 *
 * */
Route::post('/Lx190604mld/userInfo','Lx190604mldH5Controller@userInfo');   //获取/记录用户信息
Route::post('/Lx190604mld/post','Lx190604mldH5Controller@post');    //用户个人信息提交
Route::post('/Lx190604mld/message','Lx190604mldH5Controller@message');    //用户反馈




