<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use Illuminate\Http\Request;

class X201010Controller extends Common
{
    //
    protected $itemName = 'x201010';


    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        //查询总表
        $info = $this->searchSswhUser($request);
        $user = $this->userInfo($request, $info);
        return Helper::Json(1, "查询成功", ['user' => $user]);
    }
}
