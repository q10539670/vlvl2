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
 * 美年达 20190604
 *
 * */
Route::post('/Lx190604mld/userInfo', 'Lx190604mldH5Controller@userInfo'); //获取/记录用户信息
Route::post('/Lx190604mld/post', 'Lx190604mldH5Controller@post');         //用户个人信息提交
Route::post('/Lx190604mld/message', 'Lx190604mldH5Controller@message');   //用户反馈

/**
 * 保利小游戏 190716
 */
Route::get('/bl190716/user', 'Bl190716Controller@userInfo');              //获取/记录用户信息
Route::post('/bl190716/post', 'Bl190716Controller@post');                 //用户个人信息提交
Route::post('/bl190716/score', 'Bl190716Controller@score');               //用户成绩提交
Route::get('/bl190716/list', 'Bl190716Controller@list');                  //排行榜
Route::post('/bl190716/share', 'Bl190716Controller@share');               //分享

/**
 * 七夕小游戏 190726
 */
Route::get('/ls190726/user', 'Ls190726Controller@user');                  //获取/记录用户信息
Route::post('/ls190726/score', 'Ls190726Controller@score');               //用户成绩提交
Route::get('/ls190726/list', 'Ls190726Controller@list');                  //排行榜
Route::post('/ls190726/share', 'Ls190726Controller@share');               //分享

/**
 * 报名 190805
 */
Route::get('/bm190805/user', 'Bm190805Controller@user');                  //获取/记录用户信息
Route::post('/bm190805/enter', 'Bm190805Controller@enter');               //用户提交报名

/**
 * 大桥吃瓜小游戏 190808
 */
Route::get('/lx190808/user', 'Lx190808Controller@user');                  //获取/记录用户信息
Route::post('/lx190808/info', 'Lx190808Controller@info');                 //用户个人信息提交
Route::post('/lx190808/score', 'Lx190808Controller@score');               //用户成绩提交
Route::get('/lx190808/list', 'Lx190808Controller@list');                  //排行榜

/**
 * 投票活动 190822
 */
Route::get('/tp190822/user', 'Tp190822Controller@user');                   //获取/记录用户信息
Route::post('/tp190822/teams', 'Tp190822Controller@teams');                //获取参赛队伍
Route::post('/tp190822/vote', 'Tp190822Controller@vote');                  //获取参赛队伍
Route::post('/tp190822/upload', 'Tp190822Controller@uploadTeams');         //上传参赛队伍信息

/**
 * 大桥广场舞报名 190830
 */
Route::get('/bm190830/team', 'Bm190830Controller@team');                   //获取/记录用户信息
Route::post('/bm190830/enter', 'Bm190830Controller@enter');                //用户提交报名

/**
 * 保利中秋节 190902
 */
Route::get('/bt190902/user', 'Bt190902Controller@user');                   //获取/记录用户信息
Route::post('/bt190902/post', 'Bt190902Controller@post');                  //用户提交报名
Route::post('/bt190902/users', 'Bt190902Controller@users');                //获取参赛用户
Route::post('/bt190902/vote', 'Bt190902Controller@vote');                  //获取参赛用户
Route::post('/bt190902/share', 'Bt190902Controller@share');                //分享

/**
 * 大桥舞林争霸投票活动 190905
 */
Route::get('/tp190905/user', 'Tp190905Controller@user');                   //获取/记录用户信息
Route::post('/tp190905/teams', 'Tp190905Controller@teams');                //获取参赛队伍
Route::post('/tp190905/vote', 'Tp190905Controller@vote');                  //获取参赛队伍
Route::post('/tp190905/upload', 'Tp190905Controller@uploadTeams');         //上传参赛队伍信息

/**
 * 保利香颂投票页面 190911
 */
Route::get('/tp190911/user', 'Tp190911Controller@user');                   //获取/记录用户信息
Route::post('/tp190911/players', 'Tp190911Controller@players');            //获取参赛选手
Route::post('/tp190911/vote', 'Tp190911Controller@vote');                  //获取参赛选手
Route::post('/tp190911/upload', 'Tp190911Controller@uploadPlayers');       //上传参赛选手信息

/**
 * 汉江府加油 190916
 */
Route::get('/hjf190916/user', 'Hjf190916Controller@user');                 //获取/记录用户信息
Route::post('/hjf190916/post', 'Hjf190916Controller@post');                //分享加油
Route::get('/hjf190916/share', 'Hjf190916Controller@share');               //加油页面
Route::post('/hjf190916/help', 'Hjf190916Controller@help');                //分享加油
Route::get('/hjf190916/list', 'Hjf190916Controller@list');                 //排行榜

/**
 * 保利香颂音乐游戏 190920
 */
Route::get('/bl190920/user', 'Bl190920Controller@user');                   //获取/记录用户信息
Route::post('/bl190920/post', 'Bl190920Controller@post');                  //报名
Route::post('/bl190920/draw', 'Bl190920Controller@draw');                  //抽奖
Route::get('/bl190920/list', 'Bl190920Controller@list');                   //中奖名单
Route::post('/bl190920/share', 'Bl190920Controller@share');                //分享


/**
 * 保利大转盘抽奖 190929
 */
Route::post('/x190929/init', 'X190929Controller@appInitHandler');          //初始化程序
Route::get('/x190929/user', 'X190929Controller@user');                     //获取/记录用户信息
Route::post('/x190929/draw', 'X190929Controller@draw');                    //抽奖
Route::post('/x190929/post', 'X190929Controller@post');                    //中奖信息

/**
 * 保利17万现金抽奖 191008
 */
Route::get('/x191008/user', 'X191008Controller@user');                     //获取/记录用户信息
Route::post('/x191008/post', 'X191008Controller@post');                    //报名
Route::post('/x191008/shake', 'X191008Controller@shake');                  //摇一摇
Route::post('/x191008/used', 'X191008Controller@used');                    //使用

/**
 * 长沙洋湖天街 191009
 */
Route::get('/x191009/user', 'X191009Controller@user');                     //获取/记录用户信息
Route::post('/x191009/location', 'X191009Controller@setLocation');         //获取经纬度
Route::post('/x191009/post', 'X191009Controller@post');                    //提交信息
Route::post('/x191009/score', 'X191009Controller@score');                  //提交成绩
Route::post('/x191009/prize', 'X191009Controller@randomPrize');            //抽奖
Route::post('/x191009/list', 'X191009Controller@list');                    //排行榜
Route::post('/x191009/init', 'X191009Controller@appInitHandler');          //初始化程序

/**
 * 金地 191014
 */
Route::get('/x191014/user', 'X191014Controller@user');                     //获取/记录用户信息
Route::get('/x191014/shops', 'X191014Controller@shops');                   //获取所有商家信息
Route::post('/x191014/shop', 'X191014Controller@shop');                    //获取指定商家信息
Route::post('/x191014/vote', 'X191014Controller@vote');                    //投票
Route::post('/x191014/prize', 'X191014Controller@randomPrize');            //抽奖
Route::post('/x191014/comments', 'X191014Controller@comments');            //获取指定商家评论
Route::post('/x191014/submit', 'X191014Controller@submitComm');            //评论
Route::post('/x191014/upload', 'X191014Controller@uploadShop');            //上传商家信息
Route::post('/x191014/init', 'X191014Controller@appInitHandler');          //初始化程序


/**
 * 武汉江宸 191028
 */
Route::post('/x191028/init', 'X191028Controller@appInitHandler');          //初始化程序
Route::get('/x191028/user', 'X191028Controller@user');                     //获取/记录用户信息
Route::post('/x191028/post', 'X191028Controller@post');                    //提交信息
Route::post('/x191028/score', 'X191028Controller@score');                  //提交成绩
Route::post('/x191028/prize', 'X191028Controller@randomPrize');            //抽奖
Route::post('/x191028/share', 'X191028Controller@share');                  //分享

/**
 * 武汉院子报名 191029
 */
Route::get('/x191029/user', 'X191029Controller@user');                     //获取/记录用户信息
Route::post('/x191029/sign_up', 'X191029Controller@signUp');               //提交信息

/**
 * 湘中跑酷游戏 191101
 */
Route::get('/x191101/user', 'X191101Controller@user');                     //获取/记录用户信息
Route::post('/x191101/post', 'X191101Controller@post');                    //用户个人信息提交
Route::post('/x191101/score', 'X191101Controller@score');                  //用户成绩提交
Route::get('/x191101/list', 'X191101Controller@list');                     //排行榜
Route::post('/x191101/share', 'X191101Controller@share');                  //分享

/**
 * 湘中美的置业报名 191029
 */
Route::get('/x191115/user', 'X191115Controller@user');                     //获取/记录用户信息
Route::post('/x191115/sign_up', 'X191115Controller@signUp');               //提交信息

/**
 * 百事感恩节 191122
 */
Route::get('/x191122/user', 'X191122Controller@user');                   //获取/记录用户信息
Route::post('/x191122/post', 'X191122Controller@post');                   //提交信息
Route::post('/x191122/score', 'X191122Controller@score');                  //提交成绩
Route::post('/x191122/prize', 'X191122Controller@randomPrize');            //抽奖
Route::post('/x191122/list', 'X191122Controller@list');                   //排行榜
Route::post('/x191122/init', 'X191122Controller@appInitHandler');         //初始化程序

/**
 * 长沙天街感恩节 191125
 */
Route::get('/x191125/user', 'X191125Controller@user');                   //获取/记录用户信息
Route::post('/x191125/post', 'X191125Controller@post');                   //提交信息
Route::post('/x191125/prize', 'X191125Controller@randomPrize');            //抽奖
Route::post('/x191125/location', 'X191125Controller@setLocation');         //获取经纬度
Route::post('/x191125/init', 'X191125Controller@appInitHandler');         //初始化程序
Route::post('/x191125/share', 'X191125Controller@share');                 //分享

/**
 * 奥特莱斯H5抽奖 191202a
 */
Route::post('/x191202a/init', 'X191202aController@appInitHandler');         //初始化程序
Route::get('/x191202a/user', 'X191202aController@user');                    //获取/记录用户信息
Route::post('/x191202a/prize', 'X191202aController@randomPrize');           //抽奖
Route::post('/x191202a/list', 'X191202aController@list');                   //中奖记录
Route::post('/x191202a/share', 'X191202aController@share');                 //分享


/**
 * 湘中投票
 */
Route::get('/x191216/user', 'X191216Controller@user');                     //获取/记录用户信息
Route::post('/x191216/program', 'X191216Controller@programs');             //获取参赛队伍
Route::post('/x191216/vote', 'X191216Controller@vote');                    //获取参赛队伍
Route::post('/x191216/upload', 'X191216Controller@uploadProgram');         //上传参赛队伍信息

/**
 * 赤壁雍景现金红包抽奖
 */

Route::get('/x191219/user', 'X191219Controller@user');                     //获取/记录用户信息
Route::post('/x191219/post', 'X191219Controller@post');                    //提交信息

/**
 * 长沙洋湖天街 191220
 */
Route::get('/x191220/user', 'X191220Controller@user');                     //获取/记录用户信息
Route::post('/x191220/location', 'X191220Controller@setLocation');         //获取经纬度
Route::post('/x191220/post', 'X191220Controller@post');                    //提交信息
Route::post('/x191220/score', 'X191220Controller@score');                  //提交成绩
Route::post('/x191220/prize', 'X191220Controller@randomPrize');            //抽奖
Route::post('/x191220/list', 'X191220Controller@list');                    //排行榜
Route::post('/x191220/init', 'X191220Controller@appInitHandler');          //初始化程序

/**
 * 华为抽奖   200102
 */
Route::post('/x200102a/init',  'X200102Controller@appInitHandler');          //初始化程序
Route::get( '/x200102a/user',  'X200102Controller@user');                    //获取/记录用户信息
Route::post('/x200102a/post',  'X200102Controller@post');                    //提交信息
Route::post('/x200102a/prize', 'X200102Controller@randomPrize');            //抽奖
Route::post('/x200102a/share', 'X200102Controller@share');                  //分享

/**
 * 大桥   200103
 */
Route::get( '/x200103/user',  'X200103Controller@user');                    //获取/记录用户信息
Route::post('/x200103/score', 'X200103Controller@score');                  //提交成绩
Route::post('/x200103/list',  'X200103Controller@list');                     //排行榜
Route::post('/x200103/share', 'X200103Controller@share');                   //分享

/**
 * 华为抽奖   200102
 */
Route::post('/x200109/init',  'X200109Controller@appInitHandler');          //初始化程序
Route::get( '/x200109/user',  'X200109Controller@user');                    //获取/记录用户信息
Route::post('/x200109/post',  'X200109Controller@post');                    //提交信息
Route::post('/x200109/prize', 'X200109Controller@randomPrize');            //抽奖

/**
 * 湘中新年 200113
 */
Route::get( '/x200113/user', 'X200113Controller@user');                   //获取/记录用户信息
Route::post('/x200113/post', 'X200113Controller@post');                  //用户提交报名
Route::post('/x200113/users','X200113Controller@users');                //获取参赛用户
Route::post('/x200113/vote', 'X200113Controller@vote');                  //获取参赛用户
Route::post('/x200113/detail', 'X200113Controller@detail');                  //获取参赛用户



/**
 * 奥特莱斯 200114
 */
Route::get( '/x200114/user', 'X200114Controller@user');                   //获取/记录用户信息
Route::post('/x200114/score', 'X200114Controller@score');                  //用户提交报名
Route::post('/x200114/list','X200114Controller@list');                //获取参赛用户
Route::post('/x200114/share', 'X200114Controller@share');                   //分享
Route::get( '/x200114/game_num', 'X200114Controller@gameNum');                   //游戏次数
Route::get( '/x200114/add', 'X200114Controller@addGame');                   //游戏次数

/**
 * 湘中新年 200115
 */
Route::get( '/x200115/user', 'X200115Controller@user');                   //获取/记录用户信息
Route::post('/x200115/post', 'X200115Controller@post');                  //用户提交报名
Route::post('/x200115/users','X200115Controller@users');                //获取参赛用户
Route::post('/x200115/vote', 'X200115Controller@vote');                  //获取参赛用户
Route::post('/x200115/detail', 'X200115Controller@detail');                  //获取参赛用户

/**
 * 世纪山水抽奖   200117
 */
Route::post('/x200117/init',  'X200117Controller@appInitHandler');          //初始化程序
Route::get( '/x200117/user',  'X200117Controller@user');                    //获取/记录用户信息
Route::post('/x200117/post',  'X200117Controller@post');                    //提交信息
Route::post('/x200117/prize', 'X200117Controller@randomPrize');            //抽奖
Route::post('/x200117/share', 'X200117Controller@share');                  //分享

/**
 * 图片上传 200120
 */
Route::get( '/x200120/user',  'X200120Controller@user');                    //获取/记录用户信息
Route::post('/x200120/post',  'X200120Controller@post');                    //提交信息


/**
 * 百事新春
 */
Route::get( '/x200121/user',  'X200121Controller@user');                    //获取/记录用户信息
Route::post('/x200121/post',  'X200121Controller@post');                    //提交信息
Route::post('/x200121/love',  'X200121Controller@click');                    //提交信息
Route::post('/x200121/help',  'X200121Controller@help');                    //提交信息
Route::post('/x200121/list',  'X200121Controller@list');                    //提交信息
Route::post('/x200121/share',  'X200121Controller@share');                    //提交信息


/**
 * 美的情人节 200212
 */
Route::get( '/x200212/user',  'X200212Controller@user');                    //获取/记录用户信息
Route::post('/x200212/score',  'X200212Controller@score');                  //提交成绩
Route::get('/x200212/list',  'X200212Controller@list');                     //排行榜


/**
 * 美的    200305
 */
Route::get ('/x200305/user', 'X200305Controller@user');                 //获取/记录用户信息
Route::post('/x200305/post', 'X200305Controller@post');                //分享加油
Route::get('/x200305/share','X200305Controller@share');               //加油页面
Route::post('/x200305/like', 'X200305Controller@like');                //分享加油
Route::get ('/x200305/list', 'X200305Controller@list');                 //排行榜

/**
 * 武汉院子报名    200307
 */
Route::get ('/x200307/user', 'X200307Controller@user');                 //获取/记录用户信息
Route::post('/x200307/post', 'X200307Controller@post');                //报名


/**
 * 中国中铁    200312
 */
Route::post('/x200312/init',  'X200312Controller@appInitHandler');          //初始化程序
Route::get ('/x200312/user', 'X200312Controller@user');                 //获取/记录用户信息
Route::post('/x200312/topic', 'X200312Controller@topic');               //报名
Route::post('/x200312/prize', 'X200312Controller@randomPrize');         //抽奖

/**
 * 中国中铁    200413
 */
Route::get ('/x200413/user', 'X200413Controller@user');                 //获取/记录用户信息
Route::post('/x200413/post', 'X200413Controller@post');                //给自己砍价
Route::get ('/x200413/share','X200413Controller@share');               //分享砍价
Route::post('/x200413/help', 'X200413Controller@help');                //帮忙砍价
Route::get ('/x200413/list', 'X200413Controller@list');                 //排行榜

/**
 * 大桥·厨艺答题  200424
 */
Route::get ('/x200424/init', 'X200424Controller@appInitHandler');          //初始化
Route::get ('/x200424/user', 'X200424Controller@user');                    //获取/记录用户信息
Route::post('/x200424/post', 'X200424Controller@post');                    //提交信息
Route::get ('/x200424/topic','X200424Controller@topic');                   //答题
Route::post('/x200424/prize','X200424Controller@randomPrize');             //抽奖

/**
 * 中国中铁·金桥璟园    200429
 */
Route::post('/x200429/init',  'X200429Controller@appInitHandler');         //初始化程序
Route::get ('/x200429/user',  'X200429Controller@user');                   //获取/记录用户信息
Route::post('/x200429/prize', 'X200429Controller@randomPrize');            //抽奖
Route::post('/x200429/topic', 'X200429Controller@topic');                  //答题

/**
 * 中国中铁·金桥璟园    200507
 */
Route::post('/x200507/init',  'X200507Controller@appInitHandler');         //初始化程序
Route::get ('/x200507/user',  'X200507Controller@user');                   //获取/记录用户信息
Route::post('/x200507/prize', 'X200507Controller@randomPrize');            //抽奖
Route::post('/x200507/topic', 'X200507Controller@topic');                  //答题

/**
 * 奥莱520   200509
 */
Route::get( '/x200509/user',  'X200509Controller@user');                   //获取/记录用户信息
Route::post('/x200509/score', 'X200509Controller@score');                  //提交成绩
Route::post('/x200509/list',  'X200509Controller@list');                   //排行榜
Route::get('/x200509/share',  'X200509Controller@share');                  //分享

/**
 * 投票   200512
 */
Route::get( '/x200512/user',  'X200512Controller@user');                   //获取/记录用户信息
Route::get('/x200512/images', 'X200512Controller@images');                 //获取所有图片
Route::post('/x200512/poll',  'X200512Controller@poll');                   //投票
Route::post('/x200512/share',  'X200512Controller@share');                 //分享
Route::post('/x200512/upload','X200512Controller@uploadImages');           //上传
Route::post('/x200512/post',  'X200512Controller@post');                   //提交信息
Route::post('/x200512/prize','X200512Controller@randomPrize');             //抽奖
Route::post('/x200512/init',  'X200512Controller@appInitHandler');         //初始化程序

/**
 * 中国中铁·金桥璟园    200514
 */
Route::post('/x200514/init',  'X200514Controller@appInitHandler');         //初始化程序
Route::get ('/x200514/user',  'X200514Controller@user');                   //获取/记录用户信息
Route::post('/x200514/prize', 'X200514Controller@randomPrize');            //抽奖
Route::post('/x200514/topic', 'X200514Controller@topic');                  //答题

/**
 * 美好置业   200515
 */
Route::get( '/x200515/user',   'X200515Controller@user');                  //获取/记录用户信息
Route::post('/x200515/score',  'X200515Controller@score');                 //提交成绩
Route::post('/x200515/list',   'X200515Controller@list');                  //排行榜
Route::post('/x200515/post',   'X200515Controller@post');                  //提交信息
Route::get( '/x200515/share',  'X200515Controller@share');                 //分享
Route::get( '/x200515/ranking','X200515Controller@ranking');               //排名

/**
 * 中国中铁·金桥璟园    200521
 */
Route::post('/x200521/init',  'X200521Controller@appInitHandler');         //初始化程序
Route::get ('/x200521/user',  'X200521Controller@user');                   //获取/记录用户信息
Route::post('/x200521/prize', 'X200521Controller@randomPrize');            //抽奖
Route::post('/x200521/topic', 'X200521Controller@topic');                  //答题


/**
 * 山海大观    200528
 */
Route::post('/x200528/init',  'X200528Controller@appInitHandler');         //初始化程序
Route::get ('/x200528/user',  'X200528Controller@user');                   //获取/记录用户信息
Route::post('/x200528/prize', 'X200528Controller@randomPrize');            //抽奖
Route::post('/x200528/post', 'X200528Controller@post');                    //提交

/**
 * 山海大观    200528test
 */
Route::post('/x200528t/init',  'X200528testController@appInitHandler');         //初始化程序
Route::get ('/x200528t/user',  'X200528testController@user');                   //获取/记录用户信息
Route::post('/x200528t/prize', 'X200528testController@randomPrize');            //抽奖
Route::post('/x200528t/post', 'X200528testController@post');                    //提交

/**
 * 世纪山水   200612
 */
Route::get( '/x200612/user',  'X200612Controller@user');                    //获取/记录用户信息
Route::post('/x200612/score', 'X200612Controller@score');                  //提交成绩
Route::post('/x200612/list',  'X200612Controller@list');                     //排行榜
Route::post('/x200612/share', 'X200612Controller@share');                   //分享

/**
 * 金桥璟园   200615
 */
Route::post('/x200615/init', 'X200615Controller@appInitHandler');            //初始化程序
Route::get( '/x200615/user', 'X200615Controller@user');                      //获取/记录用户信息
Route::post('/x200615/post', 'X200615Controller@post');                      //提交信息
Route::post('/x200615/prize','X200615Controller@randomPrize');               //抽奖
Route::post('/x200615/share','X200615Controller@share');                     //分享
