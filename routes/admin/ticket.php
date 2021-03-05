<?php

////自动审核小票
//Route::group(['middleware'=>'ticket_demo_login'],function(){
//    Route::get('demo/index', '\App\Http\Controllers\Ticket\Demo\HomeController@index')->name('demo.index');  //首页
//    Route::get('demo/ticket/index', '\App\Http\Controllers\Ticket\Demo\TicketController@index')->name('demo.ticket.index');  //小票列表
//    Route::get('demo/user/index', '\App\Http\Controllers\Ticket\Demo\HomeController@index')->name('demo.user.index');  //用户列表
//    Route::get('demo/login/index', '\App\Http\Controllers\Ticket\Demo\LoginController@index')->name('demo.login.index');  //用户列表
//    Route::post('demo/login', '\App\Http\Controllers\Ticket\Demo\LoginController@login')->name('demo.login');  //用户列表
//    Route::post('demo/logout', '\App\Http\Controllers\Ticket\Demo\LoginController@logout')->name('demo.logout');  //用户列表
//    Route::post('demo/service/redpack', '\App\Http\Controllers\Ticket\Demo\TicketController@serviceRedpack')->name('demo.service.redpack');  //用户列表
//});
//
////统一酸菜   2019-05
//Route::group(['middleware'=>'ticket_l190514_login'],function(){
//    Route::get('l190514/index', '\App\Http\Controllers\Ticket\L190514\HomeController@index')->name('l190514.index');  //首页
//    Route::get('l190514/ticket/index', '\App\Http\Controllers\Ticket\L190514\TicketController@index')->name('l190514.ticket.index');  //小票列表
//    Route::get('l190514/user/index', '\App\Http\Controllers\Ticket\L190514\UserController@index')->name('l190514.user.index');  //用户列表
//    Route::post('l190514/user/blankList', '\App\Http\Controllers\Ticket\L190514\UserController@blankList')->name('l190514.user.blankList');  //黑名单
//    Route::get('l190514/login/index', '\App\Http\Controllers\Ticket\L190514\LoginController@index')->name('l190514.login.index');  //登录列表页面
//    Route::post('l190514/login', '\App\Http\Controllers\Ticket\L190514\LoginController@login')->name('l190514.login');  //用户列表
//    Route::post('l190514/logout', '\App\Http\Controllers\Ticket\L190514\LoginController@logout')->name('l190514.logout');  //注销
//    Route::post('l190514/service/redpack', '\App\Http\Controllers\Ticket\L190514\TicketController@serviceRedpack')->name('l190514.service.redpack');  //用户列表
//    Route::post('l190514/ticket/nopass', '\App\Http\Controllers\Ticket\L190514\TicketController@checkNoPass')->name('l190514.ticket.nopass');  //人工审核不通过
//    Route::post('l190514/ticket/pass', '\App\Http\Controllers\Ticket\L190514\TicketController@checkPass')->name('l190514.ticket.pass');  //人工审核不通过
//    Route::get('l190514/ticket/export', '\App\Http\Controllers\Ticket\L190514\TicketController@exportTickets')->name('l190514.ticket.export');  //导出excel
//    Route::post('l190514/ticket/resetredpk', '\App\Http\Controllers\Ticket\L190514\TicketController@resetRedpack')->name('l190514.ticket.resetredpk');  //重置红包
//});
//
////统一酸菜   2019-08-29
//Route::group(['middleware'=>'ticket_l190829_login'],function(){
//    Route::get('l190829/index', '\App\Http\Controllers\Ticket\L190829\HomeController@index')->name('l190829.index');  //首页
//    Route::get('l190829/ticket/index', '\App\Http\Controllers\Ticket\L190829\TicketController@index')->name('l190829.ticket.index');  //小票列表
//    Route::get('l190829/user/index', '\App\Http\Controllers\Ticket\L190829\UserController@index')->name('l190829.user.index');  //用户列表
//    Route::post('l190829/user/blankList', '\App\Http\Controllers\Ticket\L190829\UserController@blankList')->name('l190829.user.blankList');  //黑名单
//    Route::get('l190829/login/index', '\App\Http\Controllers\Ticket\L190829\LoginController@index')->name('l190829.login.index');  //登录列表页面
//    Route::post('l190829/login', '\App\Http\Controllers\Ticket\L190829\LoginController@login')->name('l190829.login');  //用户列表
//    Route::post('l190829/logout', '\App\Http\Controllers\Ticket\L190829\LoginController@logout')->name('l190829.logout');  //注销
//    Route::post('l190829/service/redpack', '\App\Http\Controllers\Ticket\L190829\TicketController@serviceRedpack')->name('l190829.service.redpack');  //用户列表
//    Route::post('l190829/ticket/nopass', '\App\Http\Controllers\Ticket\L190829\TicketController@checkNoPass')->name('l190829.ticket.nopass');  //人工审核不通过
//    Route::post('l190829/ticket/pass', '\App\Http\Controllers\Ticket\L190829\TicketController@checkPass')->name('l190829.ticket.pass');  //人工审核不通过
//    Route::get('l190829/ticket/export', '\App\Http\Controllers\Ticket\L190829\TicketController@exportTickets')->name('l190829.ticket.export');  //导出excel
//    Route::post('l190829/ticket/resetredpk', '\App\Http\Controllers\Ticket\L190829\TicketController@resetRedpack')->name('l190829.ticket.resetredpk');  //重置红包
//});

//武汉 汤达人  2019-11-27
Route::group(['middleware'=>'ticket_l191127_login'],function(){
    Route::get('l191127/index', '\App\Http\Controllers\Ticket\L191127\HomeController@index')->name('l191127.index');  //首页
    Route::get('l191127/ticket/index', '\App\Http\Controllers\Ticket\L191127\TicketController@index')->name('l191127.ticket.index');  //小票列表
    Route::get('l191127/user/index', '\App\Http\Controllers\Ticket\L191127\UserController@index')->name('l191127.user.index');  //用户列表
    Route::post('l191127/user/blankList', '\App\Http\Controllers\Ticket\L191127\UserController@blankList')->name('l191127.user.blankList');  //黑名单
    Route::get('l191127/login/index', '\App\Http\Controllers\Ticket\L191127\LoginController@index')->name('l191127.login.index');  //登录列表页面
    Route::post('l191127/login', '\App\Http\Controllers\Ticket\L191127\LoginController@login')->name('l191127.login');  //用户列表
    Route::post('l191127/logout', '\App\Http\Controllers\Ticket\L191127\LoginController@logout')->name('l191127.logout');  //注销
    Route::post('l191127/service/redpack', '\App\Http\Controllers\Ticket\L191127\TicketController@serviceRedpack')->name('l191127.service.redpack');  //用户列表
    Route::post('l191127/ticket/nopass', '\App\Http\Controllers\Ticket\L191127\TicketController@checkNoPass')->name('l191127.ticket.nopass');  //人工审核不通过
    Route::post('l191127/ticket/pass', '\App\Http\Controllers\Ticket\L191127\TicketController@checkPass')->name('l191127.ticket.pass');  //人工审核不通过
    Route::get('l191127/ticket/export', '\App\Http\Controllers\Ticket\L191127\TicketController@exportTickets')->name('l191127.ticket.export');  //导出excel
    Route::post('l191127/ticket/resetredpk', '\App\Http\Controllers\Ticket\L191127\TicketController@resetRedpack')->name('l191127.ticket.resetredpk');  //重置红包
    Route::get('l191127/act1/index', '\App\Http\Controllers\Ticket\L191127\ActivityOneController@index')->name('l191127.act1.index');  //红包列表
    Route::get('l191127/act1/export', '\App\Http\Controllers\Ticket\L191127\ActivityOneController@exportAct1')->name('l191127.act1.export');  //导出excel
    Route::get('l191127/act2/index', '\App\Http\Controllers\Ticket\L191127\ActivityTwoController@index')->name('l191127.act2.index');  //红包列表
    Route::get('l191127/act2/export', '\App\Http\Controllers\Ticket\L191127\ActivityTwoController@exportAct1')->name('l191127.act2.export');  //导出excel
});

