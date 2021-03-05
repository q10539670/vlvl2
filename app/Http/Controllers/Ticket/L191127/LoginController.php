<?php

namespace App\Http\Controllers\Ticket\L191127;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket\L191127\Admin;

class LoginController extends Controller
{
    //
    public function index()
    {
//        echo session()->get('demo_login');
        return view('ticket.'.Admin::$itemPrefix.'.login.index',['loginRouteName'=>Admin::$itemPrefix.'.login']);
    }

    /*
     * 登陆
     *
     * */
    public function login(Request $request)
    {
        $user = [];
        $userArr = Admin::loginUser();
        foreach ($userArr as $k => $u) {
            if (($request->username == $u['username']) && ($request->password == $u['passwd'])) {
                $user = $userArr[$k];
                break;
            }
        }
        if (!empty($user)) {
            session()->put(Admin::$itemPrefix.'_login', md5($user['username'] . $user['passwd'] . $user['salt']));
            return redirect()->route(Admin::$itemPrefix.'.ticket.index');
        }
        return \Redirect::back()->with('error', "用户名或密码错误");
    }

    //注销
    public function logout(Request $request)
    {
        session()->forget(Admin::$itemPrefix.'_login');
        return redirect()->route(Admin::$itemPrefix.'.login.index');
    }
}
