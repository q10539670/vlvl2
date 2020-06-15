<?php

namespace App\Http\Middleware\WxAuthV1;

use Closure;

class TdrCheckOpenid
{
    /**
     * Handle an incoming request.
     * 武汉江宸天街 授权
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
            if($authorization == config('wxauthv1.tdr.key')){
                $request->openid = config('wxauthv1.tdr.openid');
                return $next($request);
            }
        }
        return response()->json(['error' => '未授权'], 410);
    }

    /*
     * 计算 项目名称
    */
    protected function getItemName()
    {
        $urlStr = preg_replace("/(http|https):\/\//", '', $_SERVER['HTTP_REFERER']);
        return explode('/', $urlStr)[2];
    }
}
