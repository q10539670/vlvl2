<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

////三山文化 微信项目路由需要验证openid
//Route::group([
//    'prefix' => 'sswh',
//    'middleware' => 'wxAuthV1.sswhCheckOpenid',
//    'namespace' => 'Sswh'
//], function () {
//    require __DIR__ . '/wechat/sswh.php';
//});
//三山文化 微信项目路由需要验证openid
Route::group(['prefix' => 'sswh', 'namespace' => 'Sswh'], function () {
    Route::group(['middleware' => 'wxAuthV1.sswhCheckOpenid'], function () {
        require __DIR__ . '/wechat/sswh.php'; //三山文化 微信项目路由   需要openid
    });
//    Route::group(['middleware' => 'wxAuthV1.qwtCheckOpenid'], function () {
//        require __DIR__ . '/wechat/qwt.php'; //全网通 微信项目路由   需要openid
//    });
    Route::group(['middleware' => 'wxAuthV1.jctjCheckOpenid'], function () {
        require __DIR__ . '/wechat/jctj.php'; //武汉江宸天街 微信项目路由   需要openid
    });
    Route::group(['middleware' => 'wxAuthV1.tdrCheckOpenid'], function () {
        require __DIR__ . '/wechat/tdr.php'; //汤达人 微信项目路由   需要openid
    });
    Route::group(['middleware' => 'wxAuthV1.jyycCheckOpenid'], function () {
        require __DIR__ . '/wechat/jyyc.php'; //均瑶宜昌中心 微信项目路由   需要openid
    });
    Route::group(['middleware' => 'wxAuthV1.jchnCheckOpenid'], function () {
        require __DIR__ . '/wechat/jchn.php'; //均瑶宜昌中心 微信项目路由   需要openid
    });


    require __DIR__ . '/wechat/qwt-NoAuth.php'; //全网通 微信项目路由  不需要openid

    require __DIR__ . '/wechat/jctj-NoAuth.php'; //武汉江宸天街 微信项目路由  不需要openid

    require __DIR__ . '/wechat/tdr-NoAuth.php'; //汤达人 微信项目路由  不需要openid

    require __DIR__ . '/wechat/jyyc-NoAuth.php'; //均瑶宜昌中心 微信项目路由  不需要openid
//    require __DIR__ . '/admin/sswhapi.php'; //三山文化 微信项目后台列表 路由
});
Route::group(['prefix' => 'qwt', 'namespace' => 'Qwt'], function () {
    Route::group(['middleware' => 'wxAuthV1.qwtCheckOpenid'], function () {
        require __DIR__ . '/wechat/qwt.php'; //全网通 微信项目路由   需要openid
    });
    require __DIR__ . '/wechat/qwt-NoAuth.php'; //全网通 微信项目路由  不需要openid
});

Route::group(['prefix' => 'sswh', 'namespace' => 'Jyyc'], function () {
    Route::group(['middleware' => 'wxAuthV1.jyycCheckOpenid'], function () {
        require __DIR__ . '/wechat/jyyc.php'; //均瑶宜昌中心 微信项目路由   需要openid
    });
    require __DIR__ . '/wechat/jyyc-NoAuth.php'; //均瑶宜昌中心 微信项目路由  不需要openid
});
Route::group(['prefix' => 'sswh', 'namespace' => 'Jchn'], function () {
    Route::group(['middleware' => 'wxAuthV1.jchnCheckOpenid'], function () {
        require __DIR__ . '/wechat/jchn.php'; //荆楚红牛 微信项目路由   需要openid
    });
    require __DIR__ . '/wechat/jchn-NoAuth.php'; //荆楚红牛 微信项目路由  不需要openid
});

Route::group(['prefix' => 'sswh'], function () {
    Route::group(['middleware' => 'wxAuthV1.tdrCheckOpenid'], function () {
        require __DIR__ . '/wechat/tdr.php'; //汤达人 微信项目路由   需要openid
    });
    require __DIR__ . '/wechat/tdr-NoAuth.php'; //汤达人 微信项目路由  不需要openid
});

//公共路由
Route::post('/common/upload','\App\Http\Controllers\Common\UploadController@uploadSinglePic'); //上传图片
Route::post('/common/app_id','\App\Http\Controllers\Common\UserAuthorize@appId');
Route::post('/common/auth','\App\Http\Controllers\Common\UserAuthorize@auth')->name('auth.auth');
Route::post('/common/share','\App\Http\Controllers\Common\UserAuthorize@share');
Route::post('/common/view','\App\Http\Controllers\Common\UserAuthorize@wxView');

//不需要验证openid
Route::group(['prefix' => 'sswh','namespace' => 'Admin'], function () {
    Route::group(['namespace' => 'Sswh'], function () {
        require __DIR__ . '/wechat/sswh-NoAuth.php'; //三山文化 微信项目路由  不需要openid
    });

});
