<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    use Response;
    protected $ratio = 70; //富文本图片压缩比率
    /*
     * 上传单张图片
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
     * 上传多张图片
     */
    public function uploadMultiplePic($file,$path)
    {
//        $file = $request->file("images");
        if (!empty($file)) {
            foreach ($file as $key => $value) {
                $len = $key;
            }
            if ($len > 25) {
                return response()->json(['ResultData' => 6, 'info' => '最多可以上传25张图片']);
            }
            $m = 0;
            $k = 0;
            for ($i = 0; $i <= $len; $i++) {
                // $n 表示第几张图片
                $n = $i + 1;
                if ($file[$i]->isValid()) {
                    if (in_array(strtolower($file[$i]->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                        $ext = $file[$i]->getClientOriginalExtension();//获取上传文件的后缀名
                        $jpg = (string)Image::make($file[$i])->encode('jpg', $this->ratio);
                        $filename = date('Ymd') . md5(time() . rand(10000, 99999)) . "." . $ext;
                        Storage::disk('public')->put($filename, $jpg);    //保存图片
                        $pathName = strstr($filename, date('Ymd'));
                        if (env('APP_ENV') == 'local') {

                            $newFileName = asset('/lvll/storage/edtior/') . '/' . $pathName;
                        } else {
                            $newFileName = asset('/lvll/storage/edtior/') . '/' . $pathName;
                        }
                        if ($newFileName) {
                            $m = $m + 1;
                        } else {
                            $k = $k + 1;
                        }
                        $msg = $m . "张图片上传成功 " . $k . "张图片上传失败<br>";
                        $return[] = ['ResultData' => 0, 'info' => $msg, 'newFileName' => $newFileName];
                    } else {
                        return response()->json(['ResultData' => 3, 'info' => '第' . $n . '张图片后缀名不合法!<br/>' . '只支持jpeg/jpg/png/gif格式']);
                    }
                } else {
                    return response()->json(['ResultData' => 1, 'info' => '第' . $n . '张图片超过最大限制!<br/>' . '图片最大支持2M']);
                }
            }
        } else {
            return response()->json(['ResultData' => 5, 'info' => '请选择文件']);
        }
        return $return;
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
