<?php



/**
 * 红牛抽奖
 */
Route::get( '/x200701/user',    'X200701Controller@user');                     //获取/记录用户信息
Route::post('/x200701/images',  'X200701Controller@images');                   //上传小票
Route::post('/x200701/prize',   'X200701Controller@randomPrize');              //抽奖
Route::post('/x200701/post',    'X200701Controller@post');                     //提交信息
Route::post('/x200701/init',    'X200701Controller@appInitHandler');           //上传参赛队伍信息
Route::get('/x200701/log_upload','X200701Controller@uploadLog');              //上传记录
Route::get('/x200701/log_prize',    'X200701Controller@prizeLog');           //中奖记录
