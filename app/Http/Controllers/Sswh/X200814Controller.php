<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\SswhUsers;

class X200814Controller extends Common
{

    public function user()
    {
        if (!$user = SswhUsers::find(250000)) {
            return Helper::Json(-1,'查询失败');
        }
        return $this->returnJson(1, "查询成功", ['user' => $user]);
    }
}
