<?php



/**
 * 楚天地产 上传  200806
 */
Route::get('/x200806/user', 'X200806Controller@user');                    //获取/记录用户信息
Route::post('/x200806/post', 'X200806Controller@post');                    //提奖信息
Route::post('/x200806/upload', 'X200806Controller@upload');                  //上传
Route::post('/x200806/images', 'X200806Controller@images');                  //获取所有照片

/*
 * 201225 答题抽奖
 */
Route::get( '/x201225/user',  'X201225Controller@user');                    //获取/记录用户信息
Route::post('/x201225/post',  'X201225Controller@post');                    //提奖信息
Route::post('/x201225/topic', 'X201225Controller@topic');                  //答题
Route::post('/x201225/prize', 'X201225Controller@randomPrize');            //抽奖
Route::get('/x201225/share',  'X201225Controller@share');                   //分享
Route::get('/x201225/init',  'X201225Controller@appInitHandler');            //初始化
