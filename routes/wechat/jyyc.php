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
 * 宜昌中心·天宸府现金抽奖 191203
 */
Route::post('/x191203/init', 'X191203Controller@appInitHandler');         //初始化程序
Route::get('/x191203/user', 'X191203Controller@user');                   //获取/记录用户信息
Route::post('/x191203/prize', 'X191203Controller@randomPrize');            //抽奖
Route::post('/x191203/post', 'X191203Controller@post');                   //提交信息


/**
 * 宜昌中心天宸府送券 191202
 */
Route::post('/x191202/init', 'X191202Controller@appInitHandler');         //初始化程序
Route::get('/x191202/user', 'X191202Controller@user');                   //获取/记录用户信息
Route::get('/x191202/get_prize', 'X191202Controller@finalPrize');        //查询奖品信息
Route::post('/x191202/random_prize', 'X191202Controller@randomPrize');     //抽奖
Route::post('/x191202/prize', 'X191202Controller@prize');                  //领取奖品
Route::post('/x191202/post', 'X191202Controller@post');                   //信息提交
Route::post('/x191202/auth', 'X191202Controller@auth');                   //验证是否是业主
Route::post('/x191202/share', 'X191202Controller@share');                  //分享


/**
 * 宜昌中心·天宸府现金红包 191211
 */
Route::post('/x191211/init', 'X191211Controller@appInitHandler');         //初始化程序
Route::get('/x191211/user', 'X191211Controller@user');                   //获取/记录用户信息
Route::post('/x191211/prize', 'X191211Controller@randomPrize');            //抽奖
Route::post('/x191211/post', 'X191211Controller@post');                   //提交信息

/**
 * 宜昌中心·抽奖 191216a
 */
Route::post('/x191216a/init', 'X191216aController@appInitHandler');         //初始化程序
Route::get('/x191216a/user', 'X191216aController@user');                   //获取/记录用户信息
Route::post('/x191216a/post', 'X191216aController@post');                   //提交信息
Route::post('/x191216a/prize', 'X191216aController@randomPrize');            //抽奖
Route::post('/x191216a/share', 'X191216aController@share');                   //提交信息
Route::post('/x191216a/likes', 'X191216aController@likes');                   //提交信息
Route::post('/x191216a/get_prize', 'X191216aController@getPrize');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 191224 温州商会
 */
Route::post('/x191224/init', 'X191224Controller@appInitHandler');         //初始化程序
Route::get('/x191224/user', 'X191224Controller@user');                   //获取/记录用户信息
Route::post('/x191224/prize', 'X191224Controller@randomPrize');            //抽奖
Route::post('/x191224/post', 'X191224Controller@post');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 191225 钢材商会
 */
Route::post('/x191225/init', 'X191225Controller@appInitHandler');         //初始化程序
Route::get('/x191225/user', 'X191225Controller@user');                   //获取/记录用户信息
Route::post('/x191225/prize', 'X191225Controller@randomPrize');            //抽奖
Route::post('/x191225/post', 'X191225Controller@post');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 191225a 孝感商会
 */
Route::post('/x191225a/init', 'X191225aController@appInitHandler');         //初始化程序
Route::get('/x191225a/user', 'X191225aController@user');                   //获取/记录用户信息
Route::post('/x191225a/prize', 'X191225aController@randomPrize');            //抽奖
Route::post('/x191225a/post', 'X191225aController@post');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 191225b 台州商会
 */
Route::post('/x191225b/init', 'X191225bController@appInitHandler');         //初始化程序
Route::get('/x191225b/user', 'X191225bController@user');                   //获取/记录用户信息
Route::post('/x191225b/prize', 'X191225bController@randomPrize');            //抽奖
Route::post('/x191225b/post', 'X191225bController@post');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 200102  招商音乐会
 */
Route::post('/x200102/init', 'X200102Controller@appInitHandler');         //初始化程序
Route::get('/x200102/user', 'X200102Controller@user');                   //获取/记录用户信息
Route::post('/x200102/prize', 'X200102Controller@randomPrize');            //抽奖

/**
 * 宜昌中心·天宸府现金抽奖 200106  台州商会
 */
Route::post('/x200106/init', 'X200106Controller@appInitHandler');         //初始化程序
Route::get('/x200106/user', 'X200106Controller@user');                   //获取/记录用户信息
Route::post('/x200106/prize', 'X200106Controller@randomPrize');            //抽奖
Route::post('/x200106/post', 'X200106Controller@post');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 200106a 台州商会
 */
Route::post('/x200106a/init', 'X200106aController@appInitHandler');         //初始化程序
Route::get('/x200106a/user', 'X200106aController@user');                   //获取/记录用户信息
Route::post('/x200106a/prize', 'X200106aController@randomPrize');            //抽奖
Route::post('/x200106a/post', 'X200106aController@post');                   //提交信息

/**
 * 宜昌中心·天宸府现金抽奖 200108 现金红包
 */
Route::post('/x200108/init', 'X200108Controller@appInitHandler');         //初始化程序
Route::get('/x200108/user', 'X200108Controller@user');                   //获取/记录用户信息
Route::post('/x200108/prize', 'X200108Controller@randomPrize');            //抽奖
Route::post('/x200108/post', 'X200108Controller@post');                   //提交信息

/**
 * 宜昌中心·老带新--老用户 200325 现金红包
 */
Route::post('/x200325/init', 'X200325Controller@appInitHandler');         //初始化程序
Route::get('/x200325/user', 'X200325Controller@user');                   //获取/记录用户信息
Route::post('/x200325/prize', 'X200325Controller@randomPrize');            //抽奖

/**
 * 宜昌中心·老带新--新用户 200331 现金红包
 */
Route::post('/x200331/init', 'X200331Controller@appInitHandler');         //初始化程序
Route::get('/x200331/user', 'X200331Controller@user');                   //获取/记录用户信息
Route::post('/x200331/prize', 'X200331Controller@randomPrize');            //抽奖


/**
 * 宜昌中心·获取用户信息
 */
Route::get('/x200422/user', 'X200422Controller@user');                   //获取/记录用户信息
Route::post('/x200422/post', 'X200422Controller@post');

/**
 * 宜昌中心父亲节 200617
 */
Route::get('/x200617/user', 'X200617Controller@user');                    //获取/记录用户信息
Route::post('/x200617/post', 'X200617Controller@post');                   //提交信息
Route::post('/x200617/score', 'X200617Controller@score');                 //提交成绩
Route::post('/x200617/prize', 'X200617Controller@randomPrize');           //抽奖
Route::post('/x200617/list', 'X200617Controller@list');                   //排行榜
Route::post('/x200617/init', 'X200617Controller@appInitHandler');         //初始化程序

/**
 * 宜昌中心·获取用户信息
 */
Route::get('/x200629/user', 'X200629Controller@user');                   //获取/记录用户信息
Route::post('/x200629/post', 'X200629Controller@post');

/**
 * 宜昌中心  200716
 */
Route::get('/x200716/user', 'X200716Controller@user');                   //获取/记录用户信息
Route::post('/x200716/prize', 'X200716Controller@randomPrize');            //抽奖
Route::get('/x200716/init', 'X200716Controller@appInitHandler');         //初始化程序

/**
 * 物业女神投票 20200730
 */
Route::get('/x200730/user', 'X200730Controller@user');                    //获取/记录用户信息
Route::post('/x200730/vote', 'X200730Controller@vote');                   //投票
Route::get('/x200730/contestants', 'X200730Controller@contestants');      //获取所有选手


/**
 * 20200901
 */
Route::get( '/x200901/user',  'X200901Controller@user');                   //获取/记录用户信息
Route::post('/x200901/prize', 'X200901Controller@prize');                  //抽奖
Route::post('/x200901/post',  'X200901Controller@post');                   //抽奖
Route::get('/x200901/init',  'X200901Controller@appInitHandler');         //抽奖
Route::get('/x200901/share',  'X200901Controller@share');         //抽奖

/**
 * 20200928
 */
Route::get( '/x200928/user',  'X200928Controller@user');                   //获取/记录用户信息
Route::post('/x200928/prize', 'X200928Controller@randomPrize');            //抽奖
Route::post('/x200928/post',  'X200928Controller@post');                   //提交信息
Route::get( '/x200928/init',  'X200928Controller@appInitHandler');         //初始化
Route::get( '/x200928/send',  'X200928Controller@sendSms');                //发送验证码短信
Route::post('/x200928/red_pack',  'X200928Controller@sendRedPack');        //发送红包

/**
 * 201028
 */
Route::get( '/x201028/user', 'X201028Controller@user');                 //获取/记录用户信息
Route::post('/x201028/post', 'X201028Controller@post');                 //用户个人信息提交
Route::post('/x201028/score','X201028Controller@score');                //用户成绩提交
Route::get( '/x201028/list', 'X201028Controller@list');                 //排行榜