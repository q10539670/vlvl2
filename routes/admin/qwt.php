<?php

/**
 * 电信9月份抽奖 190925
 */
Route::get('/dx190925/index','Dx190925Controller@index');
Route::get('/dx190925/export','Dx190925Controller@export');

/**
 * 电信十月份抽奖 191022
 */
Route::get('/x191022/index','X191022Controller@pcIndex');
Route::get('/x191022/area','X191022Controller@pcArea');

/**
 * 电信十一月份抽奖 191119
 */
Route::get('/x191119/index','X191119Controller@pcIndex');
Route::get('/x191119/area','X191119Controller@pcArea');

/**
 * 电信十二月份抽奖 191212
 */
Route::get('/x191212/index', 'X191212Controller@index');
Route::get('/x191212/export','X191212Controller@export');
