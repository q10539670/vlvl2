<?php


namespace App\Http\Controllers\Sswh\SswhAdmin;


use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    //登录页面
    public function index()
    {
//        return '成功';
        return view('sswh.sswhAdmin.index',['title'=>'三山文化后台管理系统']);
//        return view('welcome');


    }


}