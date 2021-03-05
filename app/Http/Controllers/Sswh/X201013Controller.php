<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201013\User;
use App\Models\Sswh\X201013\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201013Controller extends Common
{
    //宜昌中铁·世纪山水--趣味书吧
    protected $itemName = 'x201013';


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
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", ['user' => $user]);
    }

    /**
     * 提交书名
     * @param  Request  $request
     * @return JsonResponse
     */
    public function submitTitle(Request $request)
    {
        if (!$user = User::where(['openid'=>$request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ], [
            'title.required' => '书名不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        foreach ($request->input('title') as $item) {
            Title::create([
                'user_id'=> $user['id'],
                'title' => $item
            ]);
        }
        return Helper::Json(1,'书名提交成功');
    }


    public function getTitleForRandom(Request $request)
    {
        if (!$num = $request->num) {
            $num = 100;
        }
        $titles = Title::where('status',1)->inRandomOrder()->take($num)->get();
        return Helper::Json(1,'查询成功',['titles'=>$titles]);
    }
}
