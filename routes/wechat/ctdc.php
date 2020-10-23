<?php



/**
 * 楚天地产 上传  200806
 */
Route::get('/x200806/user', 'X200806Controller@user');                    //获取/记录用户信息
Route::post('/x200806/post', 'X200806Controller@post');                    //提奖信息
Route::post('/x200806/upload', 'X200806Controller@upload');                  //上传
Route::post('/x200806/images', 'X200806Controller@images');                  //获取所有照片
