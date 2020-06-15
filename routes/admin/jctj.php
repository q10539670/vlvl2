<?php
/**
 * 江宸天街抽奖 191028
 */
Route::get('/x191028/index','X191028Controller@index');
Route::get('/x191028/export','X191028Controller@export');

/**
 * 江宸天街抽奖 191206
 */
Route::get('/x191206/index', 'X191206Controller@index');
Route::get('/x191206/export','X191206Controller@export');
