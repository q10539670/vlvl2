<?php

/**
 * 后台简单页面 190604
 */
Route::get('/sswhAdmin/login', 'LoginController@index');

/**
 * 报名后台页面 190805
 */
Route::get('/sswhAdmin/enter', 'Bm190805Controller@index');

Route::get('/sswhAdmin/excel/export', 'Bm190805Controller@export');

/**
 * 吃瓜小游戏后台 190808
 */
Route::get('/lx190808/index', 'Lx190808Controller@index'); //
Route::get('/lx190808/excel', 'Lx190808Controller@export'); //excel


/**
 * 大桥广场舞报名 190830
 */
Route::get('/bm190830/index', 'Bm190830Controller@index');
Route::get('/bm190830/export', 'Bm190830Controller@export');

/**
 * 保利抽奖 190929
 */
Route::get('/x190929/index', 'X190929Controller@index');
Route::get('/x190929/export', 'X190929Controller@export');

/**
 * 保利现金抽奖 191008
 */
Route::get('/x191008/index', 'X191008Controller@index');
Route::get('/x191008/export', 'X191008Controller@export');
Route::post('/x191008/prize', 'X191008Controller@prize');
Route::post('/x191008/send', 'X191008Controller@send');

/**
 * 洋湖天街 191009
 */
Route::get('/x191009/index', 'X191009Controller@index');
Route::get('/x191009/export', 'X191009Controller@export');
Route::post('/x191009/verification', 'X191009Controller@verification');

/**
 * 金地美食嘉年华 191014
 */
Route::get('/x191014/index', 'X191014Controller@index');
Route::get('/x191014/export', 'X191014Controller@export');
Route::post('/x191014/verification', 'X191014Controller@verification');
Route::get('/x191014/comm', 'X191014Controller@comments');
Route::post('/x191014/verify', 'X191014Controller@verify');
Route::delete('/x191014/del', 'X191014Controller@del');


/**
 * 湘中美的置业 191115
 */
Route::get('/x191115/index', 'X191115Controller@index');
Route::get('/x191115/export', 'X191115Controller@export');

/**
 * 百事感恩节 191122
 */
Route::get('/x191122/index', 'X191122Controller@index');
Route::get('/x191122/export', 'X191122Controller@export');

/**
 * 洋湖天街感恩节 191125
 */
Route::get('/x191125/index', 'X191125Controller@index');
Route::get('/x191125/export', 'X191125Controller@export');
Route::post('/x191125/verification', 'X191125Controller@verification');

/**
 * 奥特莱斯抽奖H5 191202a
 */
Route::get('/x191202a/index', 'X191202aController@index');
Route::get('/x191202a/export', 'X191202aController@export');

/**
 * 宜昌中心·天宸府 191203
 */
Route::get('/x191203/index', 'X191203Controller@index');
Route::get('/x191203/export', 'X191203Controller@export');

/**
 * 宜昌中心·天宸府 191202
 */
Route::get('/x191202/index', 'X191202Controller@index');
Route::get('/x191202/export', 'X191202Controller@export');

/**
 * 宜昌中心·天宸府 191211
 */
Route::get('/x191211/index', 'X191211Controller@index');
Route::get('/x191211/export', 'X191211Controller@export');

/**
 * 宜昌中心·天宸府 191216
 */
Route::get('/x191216/index', 'X191216Controller@index');
Route::get('/x191216/export', 'X191216Controller@export');

/**
 * 赤壁现金抽奖 191219
 */
Route::get('/x191219/index', 'X191219Controller@index');
Route::get('/x191219/export', 'X191219Controller@export');
Route::post('/x191219/prize', 'X191219Controller@prize');
Route::post('/x191219/send', 'X191219Controller@send');

/**
 * 洋湖天街 191220
 */
Route::get('/x191220/index', 'X191220Controller@index');
Route::get('/x191220/export', 'X191220Controller@export');
Route::post('/x191220/verification', 'X191220Controller@verification');

/**
 * 宜昌中心·天宸府 191224 温州商会
 */
Route::get('/x191224/index', 'X191224Controller@index');
Route::get('/x191224/export', 'X191224Controller@export');

/**
 * 宜昌中心·天宸府 191225 钢材商会
 */
Route::get('/x191225/index', 'X191225Controller@index');
Route::get('/x191225/export', 'X191225Controller@export');

/**
 * 宜昌中心·天宸府 191225a 孝感商会
 */
Route::get('/x191225a/index', 'X191225aController@index');
Route::get('/x191225a/export', 'X191225aController@export');

/**
 * 宜昌中心·天宸府 191225b  台州商会
 */
Route::get('/x191225b/index', 'X191225bController@index');
Route::get('/x191225b/export', 'X191225bController@export');

/**
 * 宜昌中心·天宸府 200102  招商音乐会
 */
Route::get('/x200102/index', 'X200102Controller@index');
Route::get('/x200102/export', 'X200102Controller@export');

/**
 * 宜昌中心·天宸府 200102a  华为抽奖
 */
Route::get('/x200102z/index', 'X200102aController@index');
Route::get('/x200102z/export', 'X200102aController@export');

/**
 * 宜昌中心·天宸府 200106  台州商会
 */
Route::get('/x200106/index', 'X200106Controller@index');
Route::get('/x200106/export', 'X200106Controller@export');
Route::get('/x200106r/users', 'X200106Controller@prizeUsers');    //抽奖用户
Route::get('/x200106r/round', 'X200106Controller@round');         //当前抽奖轮数
Route::post('/x200106r/set_round', 'X200106Controller@setRound');
Route::get('/x200106r/prize', 'X200106Controller@prize');//抽奖
Route::get('/x200106r/get_round', 'X200106Controller@getRound');//抽奖


/**
 * 宜昌中心·天宸府 200106a  台州商会
 */
Route::get('/x200106a/index', 'X200106aController@index');
Route::get('/x200106a/export', 'X200106aController@export');


/**
 * 大桥 200103
 */
Route::get('/x200103/index', 'X200103Controller@index');
Route::get('/x200103/export', 'X200103Controller@export');

/**
 * 宜昌中心·天宸府 200108
 */
Route::get('/x200108/index', 'X200108Controller@index');
Route::get('/x200108/export', 'X200108Controller@export');

/**
 * 百事可乐新年 200109
 */
Route::get('/x200109/index', 'X200109Controller@index');
Route::get('/x200109/export', 'X200109Controller@export');

/**
 * 湘中新年投票 200113
 */
Route::get('/x200113/index', 'X200113Controller@index');
Route::get('/x200113/export', 'X200113Controller@export');
Route::post('/x200113/delete', 'X200113Controller@delete')->name('x200113.delete');
Route::post('/x200113/black', 'X200113Controller@blackList')->name('x200113.black');

/**
 * 奥特莱斯大富翁游戏 200114
 */
Route::get('/x200114/index', 'X200114Controller@index');
Route::get('/x200114/export', 'X200114Controller@export');

/**
 * 长沙美的投票 200115
 */
Route::get('/x200115/index', 'X200115Controller@index');
Route::get('/x200115/export', 'X200115Controller@export');
Route::post('/x200115/delete', 'X200115Controller@delete')->name('x200115.delete');

/**
 * 上传图片 200120
 */
Route::get('/x200117/index', 'X200117Controller@index');
Route::get('/x200117/export', 'X200117Controller@export');

/**
 * 上传图片 200120
 */
Route::get('/x200120/index', 'X200120Controller@index');
Route::get('/x200120/export', 'X200120Controller@export');

/**
 * 百事新年 200121
 */
Route::get('/x200121/index', 'X200121Controller@index');
Route::get('/x200121/export', 'X200121Controller@export');

/**
 * 美的情人节 200212
 */
Route::get('/x200212/index', 'X200212Controller@index');
Route::get('/x200212/export', 'X200212Controller@export');

/**
 * 美的 200305
 */
Route::get('/x200305/index', 'X200305Controller@index');
Route::get('/x200305/export', 'X200305Controller@export');

/**
 * 武汉院子报名 200307
 */
Route::get('/x200307/index', 'X200307Controller@index');
Route::get('/x200307/export', 'X200307Controller@export');

/**
 * 中国中铁 200413
 */
Route::get('/x200413/index', 'X200413Controller@index');
Route::get('/x200413/export', 'X200413Controller@export');

/**
 * 宜昌中心 200422
 */
Route::get('/x200422/index', 'X200422Controller@index');
Route::get('/x200422/export', 'X200422Controller@export');

/**
 * 大桥鸡精 200424
 */
Route::get('/x200424/index', 'X200424Controller@index');
Route::get('/x200424/export', 'X200424Controller@export');

/**
 * 中国中铁·金桥璟园 200429
 */
Route::get('/x200429/index', 'X200429Controller@index');
Route::get('/x200429/export', 'X200429Controller@export');

/**
 * 武汉院子 200430
 */
Route::get('/l200430/index', 'L200430Controller@index');
Route::get('/l200430/export', 'L200430Controller@export');

/**
 * 中国中铁·金桥璟园 200507
 */
Route::get('/x200507/index', 'X200507Controller@index');
Route::get('/x200507/export', 'X200507Controller@export');

/**
 * 奥莱天生一对 200509
 */
Route::get('/x200509/index', 'X200509Controller@index');
Route::get('/x200509/export', 'X200509Controller@export');

/**
 * 投票 200512
 */
Route::get('/x200512/index', 'X200512Controller@index');
Route::get('/x200512/export', 'X200512Controller@export');

/**
 * 中国中铁·金桥璟园 200514
 */
Route::get('/x200514/index', 'X200514Controller@index');
Route::get('/x200514/export', 'X200514Controller@export');

/**
 * 美好置业 200515
 */
Route::get('/x200515/index', 'X200515Controller@index');
Route::get('/x200515/export', 'X200515Controller@export');

/**
 * 中国中铁·金桥璟园 200521
 */
Route::get('/x200521/index', 'X200521Controller@index');
Route::get('/x200521/export', 'X200521Controller@export');

/**
 * 山海大观 200528
 */
Route::get('/x200528/index', 'X200528Controller@index');
Route::get('/x200528/export', 'X200528Controller@export');

/**
 * 世纪山水 200612
 */
Route::get('/x200612/index', 'X200612Controller@index');
Route::get('/x200612/export', 'X200612Controller@export');

/**
 * 世纪山水 200615
 */
Route::get('/x200615/index', 'X200615Controller@index');
Route::get('/x200615/export', 'X200615Controller@export');

/**
 * 宜昌中心 200617
 */
Route::get('/x200617/index', 'X200617Controller@index');
Route::get('/x200617/export', 'X200617Controller@export');

/**
 * 美的 200623
 */
Route::get('/x200623/index', 'X200623Controller@index');
Route::get('/x200623/export', 'X200623Controller@export');


/**
 * 宜昌中心 建议收集  200629
 */
Route::get('/x200629/index', 'X200629Controller@index');
Route::get('/x200629/export', 'X200629Controller@export');


/**
 * 兰州·中海铂悦府  200708
 */
Route::get('/x200708/index', 'X200708Controller@index');
Route::get('/x200708/export', 'X200708Controller@export');

/**
 * 兰州·中海铂悦府  200715
 */
Route::get('/x200715/index', 'X200715Controller@index');
Route::get('/x200715/export', 'X200715Controller@export');

/**
 * 宜昌中心 生活服务启示录  200716
 */
Route::get('/x200716/index', 'X200716Controller@index');
Route::get('/x200716/export', 'X200716Controller@export');

/**
 * 大桥·龙虾节  200722
 */
Route::get('/x200722/index', 'X200722Controller@index');
Route::get('/x200722/export', 'X200722Controller@export');

/**
 * 宜昌中心·物业女神_投票  200730
 */
Route::get('/x200730/index', 'X200730Controller@index');
Route::get('/x200730/export', 'X200730Controller@export');

/**
 * 宜昌中心·投票  200731
 */
Route::get('/x200731/index', 'X200731Controller@index');
Route::get('/x200731/export', 'X200731Controller@export');


/**
 * 楚天地产·上传  200806
 */
Route::get('/x200806/index', 'X200806Controller@index');
Route::post('/x200806/delete', 'X200806Controller@delete')->name('x200806.del');


/**
 * 金地华中·第六届纳凉音乐节抽奖 200817
 */
Route::get('/x200817/index', 'X200817Controller@index');
Route::get('/x200817/export1-1', 'X200817Controller@export_site1_1');
Route::get('/x200817/export1-2', 'X200817Controller@export_site1_2');
Route::get('/x200817/export2-1', 'X200817Controller@export_site2_1');
Route::get('/x200817/export2-2', 'X200817Controller@export_site2_2');

Route::get('/x200817/round_site1', 'X200817Controller@site1Round');                   //当前抽奖轮数
Route::get('/x200817/round_site2', 'X200817Controller@site2Round');                   //当前抽奖轮数
Route::post('/x200817/set_site1round', 'X200817Controller@setSite1Round');            //设置抽奖轮数
Route::post('/x200817/set_site2round', 'X200817Controller@setSite2Round');            //设置抽奖轮数


/**
 * 金地华中·第六届纳凉音乐节_听歌识曲 200818
 */
Route::get('/x200818/index', 'X200818Controller@index');
Route::get('/x200818/export', 'X200818Controller@export');


/**
 * 金桥璟园·预约  200823
 */
Route::get('/x200823/index', 'X200823Controller@index');
Route::get('/x200823/export', 'X200823Controller@export');

/**
 * 金桥璟园·预约  200826
 */
Route::get('/x200826/index', 'X200826Controller@index');
Route::get('/x200826/export', 'X200826Controller@export');

/**
 * 宜昌中心·臻享福利  200901
 */
Route::get('/x200901/index',  'X200901Controller@index');
Route::get('/x200901/export', 'X200901Controller@export');