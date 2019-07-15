<?php

namespace App\Http\Controllers\Sswh;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
//路由地址  wx.sanshanwenhua.com/vlvl/api/sswh/test

class L190429Controller extends Controller
{
    //
    public function test()
    {
        return $this->returnJson(1,"查询成功");
    }

}
