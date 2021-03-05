<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/29
 * Time: 16:29
 */

//Route::any('/test','L190429Controller@test');
Route::any('/test2', 'L190603Controller@test2');
//Route::get('foo', function () {
//    return 'Hello World';
//});

/**
 * 武汉江宸 191028
 */
Route::post('/x191028/init', 'X191028Controller@appInitHandler');          //初始化程序
Route::get('/x191028/user', 'X191028Controller@user');                     //获取/记录用户信息
Route::post('/x191028/post', 'X191028Controller@post');                    //提交信息
Route::post('/x191028/score', 'X191028Controller@score');                  //提交成绩
Route::post('/x191028/prize', 'X191028Controller@randomPrize');            //抽奖
Route::post('/x191028/share_tl', 'X191028Controller@shareTl');             //分享到朋友圈
Route::post('/x191028/share_friend', 'X191028Controller@shareFriend');     //分享给好友

/**
 * 武汉江宸 191206
 */
Route::post('/x191206/init',   'X191206Controller@appInitHandler');         //初始化程序
Route::get( '/x191206/user',   'X191206Controller@user');                   //获取/记录用户信息
Route::post('/x191206/post',   'X191206Controller@post');                   //提交信息
Route::post('/x191206/choose', 'X191206Controller@choose');                 //选卡
Route::post('/x191206/prize',  'X191206Controller@randomPrize');            //抽奖
Route::post('/x191206/share',  'X191206Controller@share');                  //分享
Route::post('/x191206/likes',  'X191206Controller@likes');                  //点赞
Route::post('/x191206/upload',  'X191206Controller@uploadImg');             //上传海报
