<?php

namespace App\Http\Controllers\Sswh\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Helper;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Common\SswhSignupV1 as User;

/*
 * 通用报名  三山授权
 * */

class SswhSignupV1Controller extends Controller
{
    protected $config = null;
    protected $env = null;
    protected $envName = null;
    protected $itemName = null;
    protected $titleName = null;

    public function __construct()
    {
        $this->config = require(__DIR__ . '/SignupV1Config.php');
    }

    /**
     * 获取/记录 用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request)
    {
        if (!$this->validateRoute($request)) {
            return response()->json('Not Found', 404);
        }
        $user = User::where(['item_name' => $this->itemName, 'env_type' => $this->env, 'openid' => $request->openid])->first();
        $userInfo = $this->config[$this->itemName]['userInfo'] ?? false;
        if (!$user) {
            if ($userInfo) { //userInfo 授权
                $userDetail = Sswh::select('nickname', 'headimgurl')
                    ->where('openid', $request->openid)
                    ->first();
                $lastUser = User::create([
                    'env_type' => $this->env,
                    'item_name' => $this->itemName,
                    'openid' => $request->openid,
                    'nickname' => $userDetail->nickname,
                    'avatar' => $userDetail->headimgurl,
                ]);
            } else { //静默授权
                $lastUser = User::create([
                    'env_type' => $this->env,
                    'item_name' => $this->itemName,
                    'openid' => $request->openid
                ]);
            }
            $user = User::find($lastUser->id);
        }
        return Helper::json(1, '获取/记录 用户信息成功' . $this->getLabel(), ['user' => $user]);
    }

    /**
     * 提交报名信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        if (!$this->validateRoute($request)) {
            return response()->json('Not Found', 404);
        }
        if (!$user = User::where(['item_name' => $this->itemName, 'env_type' => $this->env, 'openid' => $request->openid])->first()) {
            return response()->json('未授权', 410);
        }
        //阻止重复提交
        if (!Helper::stopResubmit(__CLASS__ . ':' . __METHOD__, $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //限制重复报名
        if (isset($this->config[$this->itemName]['unique']) && (count($this->config[$this->itemName]['unique']) > 0)) {
            $uniqueCount = count($this->config[$this->itemName]['unique']);
            if ($uniqueCount == 1) {
                if (($this->config[$this->itemName]['unique'][0] == 'openid')
                    && (!$this->config[$this->itemName]['can_modify']) && (!empty($user['phone']))
                ) {
                    return response()->json('每个微信号只能报名一次', 422);
                } else if (($this->config[$this->itemName]['unique'][0] == 'phone')
                    && (User::where(['phone' => $request->phone, 'item_name' => $this->itemName, 'env_type' => $this->env])->first())
                ) {
                    return response()->json('每个手机号只能报名一次', 422);
                }
            } elseif (($uniqueCount == 2)
                && (in_array('openid', $this->config[$this->itemName]['unique']))
                && (in_array('phone', $this->config[$this->itemName]['unique']))
                && (User::where(['phone' => $request->phone, 'item_name' => $this->itemName, 'env_type' => $this->env, 'openid' => $request->openid])->first())
            ) {
                return response()->json('每个微信号对应的手机号只能报名一次', 422);
            }
        }
        $user->fill($request->only($this->config[$this->itemName]['fill_key']));
        $user->save();
        return Helper::json(1, '报名成功' . $this->getLabel(), ['user' => $user]);
    }

    //验证路由
    protected function validateRoute($request)
    {
        if (!isset($this->config[$request->item_name])) {
            return false;
        }
        $envMap = ['prod' => 1, 'test' => 2];
        if (!isset($envMap[$request->env])) {
            return false;
        }
        $this->itemName = $request->item_name;
        $this->titleName = $this->config[$request->item_name]['title'];
        $this->env = $envMap[$request->env];
        $this->envName = $envMap[$request->env] == 1 ? '生产环境' : '测试环境';
        return true;
    }

    //生产返回标签
    protected function getLabel()
    {
        $str = ' || 当前项目: 【' . $this->titleName . '】';
        $str .= ' || 当前环境: 【' . $this->envName . '】';
        return $str;
    }

    //下拉框选项

    /*
     * PC端==============================================================
     * */
    public function pcIndex(Request $request)
    {
        /*获取redis分享数据*/
        $redisShareData = Helper::getRedisShareData($this->itemName);

        $where = function ($query) use ($request) {
            if ($request->has('nameOrPhone') && ($request->nameOrPhone != '')) {
                $query->where('truename', 'like', '%' . $request->nameOrPhone . '%');
                $query->orWhere('phone', 'like', '%' . $request->nameOrPhone . '%');
            }
            $query->where('truename', '<>', '');
        };
//
//        /*查询数据*/
        $user = User::where($where)->paginate(15);

        return view('sswh.baoming.l181119a-index', [
            'exportUrl' => url('sspc/l190115b/export'),
            'paginator' => $user,
            'redisShareData' => $redisShareData,
            'title' => $this->title
        ]);
    }

    /*
     * 导出excel
     * */
    public function pcExport(Request $request)
    {
        $where = function ($query) use ($request) {
            $query->where('truename', '<>', '');
        };

        $data = User::select('*')
            ->where($where)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item, $key) {
                return [
                    '姓名' => $item['truename'],
                    '电话' => $item['phone'],
                    '报名时间' => $item['created_at']
                ];
            });

        Excel::create($this->title . date('Ymd'), function ($excel) use ($data) {

            $excel->sheet('第一页', function ($sheet) use ($data) {

                $sheet->fromArray($data);

            });

        })->download('xls');
    }

}
