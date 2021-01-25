<?php

namespace App\Http\Middleware\WxAuthV1;

use Closure;

class QwtCheckOpenid
{
    /**
     * Handle an incoming request.
     * 全网通 授权
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        (!isset($_SESSION)) && session_start();
        if(isset($_SERVER['HTTP_REFERER'])){  //正常用户
            $itemName = $this->getItemName();
            if (isset($_SESSION[$itemName])) {
                $request->openid = $_SESSION[$itemName];
                return $next($request);
            }
        }
        if($request->hasHeader('authorization')){ //调试用户
            $authorization = $request->header('authorization');
            if($authorization == config('wxauthv1.qwt.key')){
                $request->openid = config('wxauthv1.qwt.openid');
                return $next($request);
            }
        }
        return response()->json(['error' => '未授权','sessionName'=>$this->getItemName()], 410);
    }

    /*
     * 计算 项目名称
    */
    protected function getItemName() {
        $urlStr = preg_replace("/(http|https):\/\//", '', $_SERVER['HTTP_REFERER']);
        $arr = explode('/', $urlStr);
        $arr2 = array_slice($arr,2,count($arr)-3);
        return implode("/",$arr2);
    }
}
