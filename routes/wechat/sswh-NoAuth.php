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
Route::post('/Lx190604mldAdmin/postList', 'Lx190604mldAdminController@postList');   //用户提交信息列表
Route::post('/Lx190604mldAdmin/messageList', 'Lx190604mldAdminController@messageList');   //用户反馈列表


/**
 * 宜昌中心·天宸府 200106  台州商会
 */
Route::get('/x200106r/round', '\App\Http\Controllers\Admin\Sswh\X200106Controller@round');
Route::post('/x200106r/set_round', '\App\Http\Controllers\Admin\Sswh\X200106Controller@setRound');
Route::get('/x200106r/get_prize/{round}', '\App\Http\Controllers\Admin\Sswh\X200106Controller@getPrizeNum');
Route::post('/x200106r/prizes', '\App\Http\Controllers\Admin\Sswh\X200106Controller@prize');              //抽奖
Route::get('/x200106r/get_round/', '\App\Http\Controllers\Admin\Sswh\X200106Controller@getRound');              //抽奖


/**
 * 红牛接口
 */
Route::prefix('admin')->group(function () {
    Route::post('x200701/register', 'X200701Controller@register');
    Route::post('x200701/login', 'X200701Controller@login');
    Route::post('x200701/pwd/{admin}', 'X200701Controller@password');
});
Route::prefix('admin')->middleware(['x200701', 'cors'])->group(function () {
    Route::get('x200701/user', 'X200701Controller@user'); //用户
    Route::get('x200701/ticket', 'X200701Controller@ticket'); //小票
    Route::get('x200701/prize', 'X200701Controller@prizeLog'); //中奖记录
    Route::post('x200701/check', 'X200701Controller@check'); //审核
    Route::get('x200701/info', 'X200701Controller@info'); //h5信息
});

Route::get('/x200814/user', 'X200814Controller@user');   //测试

Route::post('/x200817/site1_1/prize', '\App\Http\Controllers\Sswh\X200817Site1_1Controller@prize');               //抽奖
Route::get('/x200817/site1_1/prize_user', '\App\Http\Controllers\Sswh\X200817Site1_1Controller@prizeUsers');      //获取所有抽奖人
Route::get('/x200817/site1_1/get_round', '\App\Http\Controllers\Sswh\X200817Site1_1Controller@getRound');      //获取当前轮数

Route::post('/x200817/site1_2/prize', '\App\Http\Controllers\Sswh\X200817Site1_2Controller@prize');               //抽奖
Route::get('/x200817/site1_2/prize_user', '\App\Http\Controllers\Sswh\X200817Site1_2Controller@prizeUsers');         //获取所有抽奖人
Route::get('/x200817/site1_2/get_round', '\App\Http\Controllers\Sswh\X200817Site1_2Controller@getRound');         //获取所有抽奖人

Route::post('/x200817/site2_1/prize', '\App\Http\Controllers\Sswh\X200817Site2_1Controller@prize');               //抽奖
Route::get('/x200817/site2_1/prize_user', '\App\Http\Controllers\Sswh\X200817Site2_1Controller@prizeUsers');         //获取所有抽奖人
Route::get('/x200817/site2_1/get_round', '\App\Http\Controllers\Sswh\X200817Site2_1Controller@getRound');         //获取所有抽奖人

Route::post('/x200817/site2_2/prize', '\App\Http\Controllers\Sswh\X200817Site2_2Controller@prize');               //抽奖
Route::get('/x200817/site2_2/prize_user', '\App\Http\Controllers\Sswh\X200817Site2_2Controller@prizeUsers');         //获取所有抽奖人
Route::get('/x200817/site2_2/get_round', '\App\Http\Controllers\Sswh\X200817Site2_2Controller@getRound');         //获取所有抽奖人


Route::get('/x201201/list', '\App\Http\Controllers\Sswh\X201201Controller@list');                  //当天点餐列表


Route::post('/x201218/post', '\App\Http\Controllers\Sswh\X201218Controller@apply');   //报名