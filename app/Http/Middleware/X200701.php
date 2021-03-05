<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;


class X200701
{
    /**
     * @param $request
     * @param  Closure  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     * @throws JWTException
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!auth('admins')->parseToken()->check()) {
                return response()->json(['error' => '未登录'], 401);
            }
            $request->user = auth('admins')->user();
//                 dd($request->user);
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => '未登录'], 401);
        }

    }
}
