<?php

namespace App\Http\Controllers\Ticket\L191127;

use Illuminate\Http\Request;
use App\Models\Ticket\L191127\Admin;
use App\Http\Controllers\Controller;
use App\Models\Ticket\L191127\User as User;
use App\Models\Ticket\L191127\Ticket as Ticket;


class UserController extends Controller
{

    protected $checkStatus = [
        0 => '未审核',
        10 => '审核中',
        11 => '通过 【已审核】',
        12 => '不通过 【已审核】',
        13 => '审核失败',
        20 => '红包发送中',
        21 => '红包发送 【成功】',
        22 => '红包发送 【失败】',
        23 => '抽奖失败',
        24 => '今日红包已发完'
    ];

    public function index(Request $request)
    {
        if(!$request->filled('sort_key')) $request->sort_key = 'total_money';
        if(!$request->filled('sort_order')) $request->sort_order = 'desc';

        $where = function ($query) use ($request) {
            if ($request->filled('user_id')) {
                if(preg_match("/^[0-9]*$/",$request->user_id) && User::find($request->user_id)){
                    $query->where('id',$request->user_id);
                }else{
                    $query->where('nickname','like', '%'.$request->user_id.'%');
                }
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        };

        $paginator = User::where($where)
            ->with(['tickets' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy($request->sort_key,$request->sort_order)
            ->paginate(15);

        $checkStatus = $this->checkStatus;
        return view('ticket.'.Admin::$itemPrefix.'.user.index', compact('paginator', 'checkStatus'));
    }

    public function blankList(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = 2;
        $user->save();
//        return redirect()->route('l191127.user.index')->with('notice','拉黑成功');
        return response()->json(['code' => 1, 'message' => '更新状态成功', 'data' => ['user' => $user]]);
    }


}
