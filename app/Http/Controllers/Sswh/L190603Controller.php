<?php

namespace App\Http\Controllers\Sswh;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use Illuminate\Support\Facades\DB;

class L190603Controller extends Controller
{
    //
    public function test()
    {
        return $this->returnJson(1,"查询成功");
    }


    public function test2()
    {
        return $this->returnJson(1,"查询成功",['10',9,8]);
    }


    //查询列表
    public function getList()
    {
        $users = DB::table('users')->get();
        return view('user.index', ['users' => $users]);
    }

    //查询单条
    public function getOne()
    {
        //单条
        $user = DB::table('users')->where('name', '学院君')->first();
        //某个值
        $user = DB::table('users')->where('id',1)->value('name');
    }

}
