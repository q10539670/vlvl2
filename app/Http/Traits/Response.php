<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/29
 * Time: 15:50
 */
namespace App\Http\Traits;

trait Response{

    /*
     * 标准返回
     * */
    public function returnJson($code = 1, $message = '', $data = [], $type = false){
        if ($type) {
            return json_encode(['code' => $code, 'message' => $message, 'data' => $data], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

}