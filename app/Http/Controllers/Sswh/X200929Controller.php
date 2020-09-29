<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200929\User;
use Illuminate\Http\Request;

class X200929Controller extends Common
{
    //武汉院子
    protected $itemName = 'x200929';


    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info);
            $userInfo['path'] = $this->download_remote_pic($userInfo['avatar'],$this->itemName,$userInfo['openid']);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::Json(1, "查询成功", ['user' => $user]);
    }

    function download_remote_pic($url,$path,$openid){
        $header = [
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate',
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
        }
        $img_content=$imgBase64Code;//图片内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {
            $type = $result[2];//得到图片类型png jpg gif
            //相对路径
            $relative_path='/statics/wx_items/'. $path."/";
            //绝对路径（$_SERVER['DOCUMENT_ROOT']为网站根目录）
            $absolute_path = $_SERVER['DOCUMENT_ROOT'].$relative_path;
            if(!file_exists($absolute_path)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($absolute_path, 0755);
            }
            //文件名
            $filename=$openid.".{$type}";
            $new_file = $absolute_path.$filename;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img_content)))) {
                return $relative_path.$filename;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
