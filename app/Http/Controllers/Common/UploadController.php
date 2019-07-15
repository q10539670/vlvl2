<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Response;

class UploadController extends Controller
{
    use Response;

    /*
     * 上传图片
     * */
    public function uploadSinglePic(Request $request)
    {
        if(!$this->validationReferer()){
            return response()->json(['error' => '未授权'], 410);
        }
        $validator = \Validator::make($request->all(), [
            'img' => 'required|image|max:' . (1024 * 6),
            'item_name' => 'required|max:16'
        ], [
            'img.required' => '上传图片不能未空',
            'img.image' => '上传类型只能是图片',
            'img.max' => '图片大小不能超过6M',
            'item_name.required' => '项目名称不能为空',
            'item_name.max' => '项目名称太长(最长16位)',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('img')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        if (!$path = $request->img->store('/upload/items/' . $request->item_name . '/' . date('Ymd'))) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        return $this->returnJson(1, '上传图片 成功', [
            'storePath' => 'storage/' . $path,
            'prefix' => env('APP_URL')
        ]);
    }

    /*
     * 验证referer
    */
    protected function validationReferer()
    {
        if(!isset($_SERVER['HTTP_REFERER'])){
            return false;
        }
        $urlStr = preg_replace("/(http|https):\/\//", '', $_SERVER['HTTP_REFERER']);
        if(explode('/', $urlStr)[0] != 'wx.sanshanwenhua.com'){
            return false;
        }
        return true;
    }
}
