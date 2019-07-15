<?php


namespace App\Http\Controllers\Sswh;


use App\Http\Controllers\Common\BaseV1Controller as Controller;
use Illuminate\Http\Request;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Lx190604mld\User as User;
use App\Models\Sswh\Lx190604mld\Post as Post;
use App\Models\Sswh\Lx190604mld\Message as Message;
use Illuminate\Support\Facades\Validator;

class Lx190604mldH5Controller extends Controller
{

    /*
     * 获取/记录用户授权信息
     * */
    public function userInfo(Request $request)
    {
//        return 1111;
        $openid = $request->openid;
        if(!$user = User::where(['openid' => $openid])->first()){
            //查询总表
            $info = Sswh::select('nickname', 'headimgurl')
                ->where('openid', $openid)
                ->first();
            //新增数据到用户表中
            User::create([
                'openid' => $openid,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
            ]);
            //查询
            $user = User::where(['openid'=>$openid])->first();
        }

        return $this->returnJson(1,"查询成功",['user'=>$user]);
    }


    /*
     * 用户个人信息提交
     * */
    public function post(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
//        die;

        //检查信息
        $validator = Validator::make($request->all(), [
            'user_address' => 'required',
            'user_name' => 'required',
            'user_phone' => 'required',
            'user_sale' => 'required'
        ], [
            'user_address.required' => '地址不能为空',
            'user_name.required' => '姓名不能为空',
            'user_phone.required' => '电话号码不能为空',
            'user_sale.required' => '销售人员不能为空',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
//        return $this->returnJson(1,"提交成功");
        //新增用户提交信息
        $post = Post::create([
            'user_id' => $user->id,
            'user_address' => $request->user_address,
            'user_name' => $request->user_name,
            'user_phone' => $request->user_phone,
            'user_sale' => $request->user_sale,
        ]);

        //用户提交信息个数增加
        $user->post_num++;
        $user->save();

        return $this->returnJson(1,"提交成功",['post'=>$post]);

    }



    /*
     * 用户反馈
     * */
    public function message(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'message' => 'required',

        ], [
            'message.required' => '反馈内容不能为空',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
//        return $this->returnJson(1,"提交成功");
        //新增用户提交信息
        $message = Message::create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        //用户反馈数目增加
        $user->message_num++;
        $user->save();

        return $this->returnJson(1,"反馈成功",['message'=>$message]);

    }






}