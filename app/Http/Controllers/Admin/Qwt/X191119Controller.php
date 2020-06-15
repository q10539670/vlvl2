<?php

namespace App\Http\Controllers\Admin\Qwt;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Helper;

use App\Models\Qwt\X191119\User;
use App\Models\China;


class X191119Controller extends Controller
{

    protected $itemName = 'x191119';
    protected $title = '全网通（11月）答题红包抽奖';

    /*
     * 浏览量 分享 统计接口
     * */
    public function redisStatistics(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'item_name' => 'required|min:0|max:50',   //纬度
            ],
            [
                'item_name.required' => '项目名不能为空',
                'item_name.min' => '项目名错误',
                'item_name.max' => '项目名错误',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $redisShareData = Helper::getRedisShareData($request->item_name);
        return Helper::json(1, '浏览量 分享数 查询成功', ['count' => $redisShareData]);
    }

    public function pcIndex(Request $request)
    {
        /*获取redis分享数据*/
        $countData = $this->redpackCount();

        $where = function ($query) use ($request) {
            if ($request->has('nickname') && ($request->nickname != '')) {
                $userArr = User::where('nickname', 'like', '%' . $request->nickname . '%')->get()->toArray();
                $idArr = [];
                foreach ($userArr as $user) {
                    $idArr[] = $user['id'];
                }
                $query->whereIn('id', $idArr);
            }
            if ($request->has('address') && ($request->address != '')) {
                if ($address = China::where("name", 'like', '%' . $request->address . '%')->first()) {
                    $ads = (new China())->getAllChildArea($address->id);
                    $query->whereIn('virtual_address_code',$ads);
                }
            }
            if ($request->has('startTime') && ($request->startTime != '')) {
                $query->whereBetween('created_at', [$request->startTime, $request->endTime]);
            }
        };

        /*查询数据*/
        $user = User::where($where)->orderBy('created_at', 'asc')->paginate(15);
//        dd($user);
        return view('sswh.qwt.'.$this->itemName.'.index', [
            'paginator' => $user,
            'countData' => $countData,
            'title' => $this->title,
            'areaUrl' => 'https://wx.sanshanwenhua.com/vlvl/' . $this->itemName . '/area'
        ]);
    }

    /*
     * 区域统计页面
     * */
    public function pcArea(Request $request)
    {
        /*获取redis分享数据*/
        $countData = $this->redpackCount();

        $redis = app('redis');
        $redis->select(12);
        $cacheKey = 'admin:' . $this->itemName . ':area';
        $nowTime = time();
        $expireSecond = 60 * 5;
        if (!$redis->exists($cacheKey)) {
            $redis->hset($cacheKey, 'expire_at', $nowTime - 1);
        }
        if ($redis->hget($cacheKey, 'expire_at') < $nowTime) {
            $areaData = $this->getAreaCountData();
            $cacheData = [];
            $cacheData['hubei'] = json_encode($areaData['hubei'], JSON_UNESCAPED_UNICODE);
            $cacheData['shengs'] = json_encode($areaData['shengs'], JSON_UNESCAPED_UNICODE);
            $cacheData['updated_at'] = date('Y-m-d H:i:s');
            $cacheData['format_hubei'] = json_encode($this->getFormatBarData($areaData['hubei']), JSON_UNESCAPED_UNICODE);
            $cacheData['format_shengs'] = json_encode($this->getFormatBarData($areaData['shengs']), JSON_UNESCAPED_UNICODE);
            $cacheData['expire_at'] = $nowTime + $expireSecond;
            $redis->hmset($cacheKey, $cacheData);
        }

        $shengsBarData = $redis->hget($cacheKey, 'format_shengs');
        $hubeiBarData = $redis->hget($cacheKey, 'format_hubei');
        $hubeiTask = $redis->hget($cacheKey, 'hubei');
        $updated_at = $redis->hget($cacheKey, 'updated_at');

//        dd(json_decode($hubeiBarData,true)['name']);
        return view('sswh.qwt.'.$this->itemName.'.area', [
            'quanguo' => json_decode($shengsBarData, true),
            'hubei' => json_decode($hubeiBarData, true),
            'hubei_task' => json_decode($hubeiTask, true),
            'updated_at' => $updated_at,
            'countData' => $countData,
            'title' => $this->title,
            'indexUrl' => 'https://wx.sanshanwenhua.com/vlvl/' . $this->itemName . '/index'
        ]);
    }

    /*
     * 用户列表
     * */
    public function usersList(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('nickname') && ($request->nickname != '')) {
                if ($userArr = User::where('nickname', 'like', '%' . $request->nickname . '%')->get()->toArray()) {
                    $idArr = [];
                    foreach ($userArr as $user) {
                        $idArr[] = $user['id'];
                    }
                    $query->whereIn('id', $idArr);
                }
            }
            if ($request->has('start') && ($request->start != '')) {
                $query->where('created_at', '>', $request->start);
            }
            if ($request->has('end') && ($request->end != '')) {
                $query->where('created_at', '<', $request->end);
            }
        };
        $perPage = $request->has('per_page') ? $request->per_page : 3; //每页条数
        $currentPage = $request->has('current_page') ? $request->current_page : 1;  //当前页
        $total = User::where($where)->count();       // 数据条数
        $totalPage = ceil($total / $perPage);   // 总页数
        $items = User::where($where)->orderBy('created_at', 'asc')
            ->offset($perPage * ($currentPage - 1))
            ->limit($perPage)->get()->toArray();
        return Helper::json(1, '用户列表 查询成功', [
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total' => $total,
            'total_page' => $totalPage,
            'items' => $items,
            'zjl' => (new User())->getZJLv()
        ]);
    }

    /*
     * 红包统计
     * */
    public function redpackCount()
    {
        $dateArr = [
//            'test',
            '20191122',
            '20191123',
            '20191124'
        ];
        $redisShareData = Helper::getRedisShareData('x_20191119');
        $res = [];
        $res['viewNum'] = $redisShareData['view'] ?? 0;
        $res['shareFriendNum'] = $redisShareData['firend'] ?? 0;
        $res['shareFriendsNum'] = $redisShareData['tl'] ?? 0;
        $res['userNum'] = User::count();
        $res['registerNum'] = User::whereIn('status', [1, 2, 3])->count();
        $res['totalMoney'] = 3500;
        $res['totalRedpackNum'] = 2950;
        $res['zeroMoneyHubei'] = $res['oneMoneyHubei'] = $res['twoMoneyHubei'] = $res['fiveMoneyHubei'] =
        $res['zeroMoneyOther'] = $res['oneMoneyOther'] = $res['twoMoneyOther'] = $res['fiveMoneyOther'] = 0;
        $redis = app('redis');
        $redis->select(12);
        foreach ($dateArr as $dateStr) {
            $redisPrizeCountKey = 'qwt:x191119:prizeCount';
            $hubei = $redis->hGetAll($redisPrizeCountKey . ':hubei:' . $dateStr);
            $other = $redis->hGetAll($redisPrizeCountKey . ':other:' . $dateStr);
            $res['zeroMoneyHubei'] += $hubei[0] ?? 0;
            $res['oneMoneyHubei'] += $hubei[1] ?? 0;
            $res['twoMoneyHubei'] += $hubei[2] ?? 0;
            $res['fiveMoneyHubei'] += $hubei[3] ?? 0;
            $res['zeroMoneyOther'] += $other[0] ?? 0;
            $res['oneMoneyOther'] += $other[1] ?? 0;
            $res['twoMoneyOther'] += $other[2] ?? 0;
            $res['fiveMoneyOther'] += $other[3] ?? 0;
        }
        $res['prizeUserNumHubei'] = $res['oneMoneyHubei'] + $res['twoMoneyHubei'] + $res['fiveMoneyHubei'];
        $res['prizeUserNumOther'] = $res['oneMoneyOther'] + $res['twoMoneyOther'] + $res['fiveMoneyOther'];
        $res['prizeMoneyNumHubei'] = $res['oneMoneyHubei'] * 1 + $res['twoMoneyHubei'] * 2 + $res['fiveMoneyHubei'] * 3;
        $res['prizeMoneyNumOther'] = $res['oneMoneyOther'] * 1 + $res['twoMoneyOther'] * 2 + $res['fiveMoneyOther'] * 3;
        $res['zjlv'] = ($res['registerNum'] > 0) ? (round(($res['prizeUserNumHubei'] + $res['prizeUserNumOther']) / $res['registerNum'], 4) * 100) . '%' : '--';
        $res['zeroMoneyHubei'] = User::whereIn('status', [2, 3])->count();
        $res['zeroMoneyOther'] = 0;
        $res['zjlv2'] = (new User())->getZJLv();
        return $res;
    }


    public function test3()
    {

        $res = [];

        $china = new China();

        $a = China::where("pid", "420000")->get()->toArray();

        foreach ($a as $k => $vv) {
            $areaIdArr = $china->getAllChildArea($vv['id']);
            foreach ($areaIdArr as $v) {
                $res[$vv["name"]]["定位"][$china->where('id', $v)->first()->toArray()['name']] = User::where('address_code', $v)->count();
                $res[$vv["name"]]["用户选择"][$china->where('id', $v)->first()->toArray()['name']] = User::where('virtual_address_code', $v)->count();
            }
        }
        return $res;
    }

    public function test1()
    {
        $redis = app('redis');
        $redis->select(12);
        $cacheKey = 'admin:l190826a:area';
        $shengs = $redis->hget($cacheKey, 'shengs');
        $hubei = $redis->hget($cacheKey, 'hubei');
        return Helper::json(1, 'eCharts 数据查询成功', [
            'shengs' => json_decode($shengs, true),
            'hubei' => json_decode($hubei, true),
        ]);
        $res = [];
        $china = new China();
//        $areaIdArr = $china->getAllChildArea(420000);
//        $res['one'] = User::whereIn('virtual_address_code', $areaIdArr)->where('status', 1)->where('money', 1)->count();
//        $res['two'] = User::whereIn('virtual_address_code', $areaIdArr)->where('status', 1)->where('money', 2)->count();
//        $res['five'] = User::whereIn('virtual_address_code', $areaIdArr)->where('status', 1)->where('money', 5)->count();
        $areaIdArr = $china->getAllChildArea(null);
        $res['one'] = User::whereIn('virtual_address_code', $areaIdArr)->where('status', 1)->where('money', 1)->count();
        $res['two'] = User::whereIn('virtual_address_code', $areaIdArr)->where('status', 1)->where('money', 2)->count();
        $res['five'] = User::whereIn('virtual_address_code', $areaIdArr)->where('status', 1)->where('money', 3)->count();
        return $res;
    }

    /*
     * 柱状图 数据
     * */
    public function barData()
    {
        $redis = app('redis');
        $redis->select(12);
        $cacheKey = 'admin:' . $this->itemName . ':area';
        $nowTime = time();
        $expireSecond = 60 * 5;
        if (!$redis->exists($cacheKey)) {
            $redis->hset($cacheKey, 'expire_at', $nowTime - 1);
        }
        if ($redis->hget($cacheKey, 'expire_at') < $nowTime) {
            $areaData = $this->getAreaCountData();
            $cacheData = [];
            $cacheData['hubei'] = json_encode($areaData['hubei'], JSON_UNESCAPED_UNICODE);
            $cacheData['shengs'] = json_encode($areaData['shengs'], JSON_UNESCAPED_UNICODE);
            $cacheData['updated_at'] = date('Y-m-d H:i:s');
            $cacheData['format_hubei'] = json_encode($this->getFormatBarData($areaData['hubei']), JSON_UNESCAPED_UNICODE);
            $cacheData['format_shengs'] = json_encode($this->getFormatBarData($areaData['shengs']), JSON_UNESCAPED_UNICODE);
            $cacheData['expire_at'] = $nowTime + $expireSecond;
            $redis->hmset($cacheKey, $cacheData);
        }
        $shengsBarData = $redis->hget($cacheKey, 'format_shengs');
        $hubeiBarData = $redis->hget($cacheKey, 'format_hubei');
        $updated_at = $redis->hget($cacheKey, 'updated_at');
        return Helper::json(1, 'eCharts 数据查询成功', [
            'shengs' => $shengsBarData,
            'hubei' => $hubeiBarData,
            'updated_at' => $updated_at
        ]);
    }

    /*
     * 获取地区统计数据
     * */
    protected function getAreaCountData()
    {
        $china = new China();
        // 查询省级数据
        $shengs = China::whereNull('pid')->get();
        $shengsCount = [];
        foreach ($shengs as $k => $v) {
            $areaIdArr = $china->getAllChildArea(intval($v['id']));
            $shengsCount[] = [
                'name' => $v['name'],
//                'currentNum' => User::/*where('status',1)->*/ whereIn('address_code', $areaIdArr)->count(), //统计用户选择的
                'currentNum' => User::/*where('status',1)->*/whereIn('address_code', $areaIdArr)->count(),  //统计经纬度定位的
            ];
        }
        // 查询湖北地区的数据
        $hbAreaIdArr = $this->getHubeiIdArr();
        foreach ($hbAreaIdArr as $k => $v) {
            $areaIdArr = $china->getAllChildArea(intval($v['areaId']));
//            $hbAreaIdArr[$k]['currentNum'] = User::whereIn('address_code', $areaIdArr)/*->where('status',1)*/ ->count(); //统计用户选择的
            $hbAreaIdArr[$k]['currentNum'] = User::whereIn('address_code', $areaIdArr)/*->where('status',1)*/->count(); //统计经纬度定位的
            $hbAreaIdArr[$k]['v'] = 100 * round($hbAreaIdArr[$k]['currentNum'] / $hbAreaIdArr[$k]['targetNum'], 4) . '%';
        }
        return ['hubei' => $hbAreaIdArr, 'shengs' => $shengsCount];
    }

    /*
     * 格式化 eCharts 数据
     * */
    protected function getFormatBarData($arr)
    {
        $res = [];
        $length = count($arr);
        foreach ($arr as $k => $v) {
            if ($k == 0) {
                $res['name'] = '[';
                $res['value'] = '[';
            } else {
                $res['name'] .= ',';
                $res['value'] .= ',';
            }
            $res['name'] .= '"' . $v['name'] . '"';
            $res['value'] .= $v['currentNum'];
            if ($k == ($length - 1)) {
                $res['name'] .= ']';
                $res['value'] .= ']';
            }
        }
        return $res;
    }

    /*
     * 获取湖北区域信息模板
     * */
    protected function getHubeiIdArr()
    {
        return [
            ['areaId' => '420100', 'name' => '武汉', 'targetNum' => 1000, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420600', 'name' => '襄阳', 'targetNum' => 390, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420500', 'name' => '宜昌', 'targetNum' => 390, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '421000', 'name' => '荆州', 'targetNum' => 390, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '421100', 'name' => '黄冈', 'targetNum' => 390, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '422800', 'name' => '恩施', 'targetNum' => 220, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420800', 'name' => '荆门', 'targetNum' => 220, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420300', 'name' => '十堰', 'targetNum' => 220, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '421200', 'name' => '咸宁', 'targetNum' => 220, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420900', 'name' => '孝感', 'targetNum' => 220, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420200', 'name' => '黄石', 'targetNum' => 220, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '421300', 'name' => '随州', 'targetNum' => 120, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '420700', 'name' => '鄂州', 'targetNum' => 100, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '429005', 'name' => '潜江', 'targetNum' => 100, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '429004', 'name' => '仙桃', 'targetNum' => 100, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '429006', 'name' => '天门', 'targetNum' => 100, 'currentNum' => 0, 'v' => ''],
            ['areaId' => '429021', 'name' => '神龙架林区', 'targetNum' => 20, 'currentNum' => 0, 'v' => ''],
        ];
    }
    /*
    电信 7 月
     {
        "武汉市": {
            "定位": {
                "新洲区": 18,
                "黄陂区": 41,
                "江夏区": 75,
                "蔡甸区": 43,
                "汉南区": 6,
                "东西湖区": 37,
                "洪山区": 134,
                "青山区": 22,
                "武昌区": 79,
                "汉阳区": 36,
                "硚口区": 40,
                "江汉区": 64,
                "江岸区": 102,
                "市辖区": 0,
                "武汉市": 0
            },
            "用户选择": {
                "新洲区": 10,
                "黄陂区": 20,
                "江夏区": 22,
                "蔡甸区": 19,
                "汉南区": 3,
                "东西湖区": 30,
                "洪山区": 69,
                "青山区": 29,
                "武昌区": 134,
                "汉阳区": 90,
                "硚口区": 79,
                "江汉区": 135,
                "江岸区": 204,
                "市辖区": 0,
                "武汉市": 0
            }
        },
        "黄石市": {
            "定位": {
                "大冶市": 59,
                "阳新县": 26,
                "铁山区": 1,
                "下陆区": 21,
                "西塞山区": 36,
                "黄石港区": 23,
                "市辖区": 0,
                "黄石市": 0
            },
            "用户选择": {
                "大冶市": 43,
                "阳新县": 23,
                "铁山区": 8,
                "下陆区": 21,
                "西塞山区": 40,
                "黄石港区": 4803,
                "市辖区": 0,
                "黄石市": 0
            }
        },
        "十堰市": {
            "定位": {
                "丹江口市": 12,
                "房县": 8,
                "竹溪县": 6,
                "竹山县": 15,
                "郧西县": 14,
                "郧阳区": 10,
                "张湾区": 28,
                "茅箭区": 71,
                "市辖区": 0,
                "十堰市": 0
            },
            "用户选择": {
                "丹江口市": 13,
                "房县": 9,
                "竹溪县": 5,
                "竹山县": 15,
                "郧西县": 17,
                "郧阳区": 8,
                "张湾区": 25,
                "茅箭区": 59,
                "市辖区": 0,
                "十堰市": 0
            }
        },
        "宜昌市": {
            "定位": {
                "枝江市": 12,
                "当阳市": 9,
                "宜都市": 4,
                "五峰土家族自治县": 1,
                "长阳土家族自治县": 21,
                "秭归县": 7,
                "兴山县": 8,
                "远安县": 9,
                "夷陵区": 31,
                "猇亭区": 7,
                "点军区": 0,
                "伍家岗区": 18,
                "西陵区": 47,
                "市辖区": 0,
                "宜昌市": 0
            },
            "用户选择": {
                "枝江市": 8,
                "当阳市": 7,
                "宜都市": 5,
                "五峰土家族自治县": 2,
                "长阳土家族自治县": 21,
                "秭归县": 3,
                "兴山县": 7,
                "远安县": 9,
                "夷陵区": 40,
                "猇亭区": 23,
                "点军区": 5,
                "伍家岗区": 28,
                "西陵区": 30,
                "市辖区": 0,
                "宜昌市": 0
            }
        },
        "襄阳市": {
            "定位": {
                "宜城市": 28,
                "枣阳市": 74,
                "老河口市": 46,
                "保康县": 8,
                "谷城县": 18,
                "南漳县": 9,
                "襄州区": 63,
                "樊城区": 157,
                "襄城区": 57,
                "市辖区": 0,
                "襄阳市": 0
            },
            "用户选择": {
                "宜城市": 26,
                "枣阳市": 58,
                "老河口市": 46,
                "保康县": 14,
                "谷城县": 32,
                "南漳县": 16,
                "襄州区": 60,
                "樊城区": 120,
                "襄城区": 58,
                "市辖区": 0,
                "襄阳市": 0
            }
        },
        "鄂州市": {
            "定位": {
                "鄂城区": 97,
                "华容区": 26,
                "梁子湖区": 2,
                "市辖区": 0,
                "鄂州市": 0
            },
            "用户选择": {
                "鄂城区": 100,
                "华容区": 22,
                "梁子湖区": 2,
                "市辖区": 0,
                "鄂州市": 0
            }
        },
        "荆门市": {
            "定位": {
                "钟祥市": 15,
                "沙洋县": 11,
                "京山县": 0,
                "掇刀区": 24,
                "东宝区": 44,
                "市辖区": 0,
                "荆门市": 0
            },
            "用户选择": {
                "钟祥市": 11,
                "沙洋县": 14,
                "京山县": 18,
                "掇刀区": 23,
                "东宝区": 49,
                "市辖区": 0,
                "荆门市": 0
            }
        },
        "孝感市": {
            "定位": {
                "汉川市": 24,
                "安陆市": 25,
                "应城市": 1,
                "云梦县": 7,
                "大悟县": 28,
                "孝昌县": 14,
                "孝南区": 85,
                "市辖区": 0,
                "孝感市": 0
            },
            "用户选择": {
                "汉川市": 17,
                "安陆市": 18,
                "应城市": 4,
                "云梦县": 7,
                "大悟县": 24,
                "孝昌县": 13,
                "孝南区": 94,
                "市辖区": 0,
                "孝感市": 0
            }
        },
        "荆州市": {
            "定位": {
                "松滋市": 9,
                "洪湖市": 13,
                "石首市": 5,
                "江陵县": 0,
                "监利县": 13,
                "公安县": 3,
                "荆州区": 11,
                "沙市区": 23,
                "市辖区": 0,
                "荆州市": 0
            },
            "用户选择": {
                "松滋市": 6,
                "洪湖市": 8,
                "石首市": 2,
                "江陵县": 0,
                "监利县": 12,
                "公安县": 3,
                "荆州区": 12,
                "沙市区": 22,
                "市辖区": 0,
                "荆州市": 0
            }
        },
        "黄冈市": {
            "定位": {
                "武穴市": 14,
                "麻城市": 27,
                "黄梅县": 18,
                "蕲春县": 20,
                "浠水县": 17,
                "英山县": 21,
                "罗田县": 7,
                "红安县": 18,
                "团风县": 3,
                "黄州区": 31,
                "市辖区": 0,
                "黄冈市": 0
            },
            "用户选择": {
                "武穴市": 7,
                "麻城市": 9,
                "黄梅县": 11,
                "蕲春县": 18,
                "浠水县": 11,
                "英山县": 6,
                "罗田县": 8,
                "红安县": 14,
                "团风县": 1,
                "黄州区": 48,
                "市辖区": 0,
                "黄冈市": 0
            }
        },
        "咸宁市": {
            "定位": {
                "赤壁市": 37,
                "通山县": 18,
                "崇阳县": 17,
                "通城县": 18,
                "嘉鱼县": 16,
                "咸安区": 69,
                "市辖区": 0,
                "咸宁市": 0
            },
            "用户选择": {
                "赤壁市": 35,
                "通山县": 19,
                "崇阳县": 13,
                "通城县": 14,
                "嘉鱼县": 10,
                "咸安区": 62,
                "市辖区": 0,
                "咸宁市": 0
            }
        },
        "随州市": {
            "定位": {
                "广水市": 29,
                "随县": 23,
                "曾都区": 52,
                "市辖区": 0,
                "随州市": 0
            },
            "用户选择": {
                "广水市": 26,
                "随县": 17,
                "曾都区": 47,
                "市辖区": 0,
                "随州市": 0
            }
        },
        "恩施土家族苗族自治州": {
            "定位": {
                "鹤峰县": 3,
                "来凤县": 5,
                "咸丰县": 2,
                "宣恩县": 11,
                "巴东县": 5,
                "建始县": 6,
                "利川市": 8,
                "恩施市": 24,
                "恩施土家族苗族自治州": 0
            },
            "用户选择": {
                "鹤峰县": 1,
                "来凤县": 4,
                "咸丰县": 3,
                "宣恩县": 7,
                "巴东县": 1,
                "建始县": 4,
                "利川市": 7,
                "恩施市": 23,
                "恩施土家族苗族自治州": 0
            }
        },
        "省直辖县级行政区划": {
            "定位": {
                "神农架林区": 16,
                "天门市": 55,
                "潜江市": 32,
                "仙桃市": 120,
                "省直辖县级行政区划": 0
            },
            "用户选择": {
                "神农架林区": 18,
                "天门市": 48,
                "潜江市": 18,
                "仙桃市": 100,
                "省直辖县级行政区划": 0
            }
        }
    }

    电信6月
    {
        "武汉市": {
            "定位": {
                "新洲区": 25,
                "黄陂区": 46,
                "江夏区": 95,
                "蔡甸区": 59,
                "汉南区": 3,
                "东西湖区": 36,
                "洪山区": 203,
                "青山区": 34,
                "武昌区": 69,
                "汉阳区": 38,
                "硚口区": 39,
                "江汉区": 57,
                "江岸区": 103,
                "市辖区": 0,
                "武汉市": 0
            },
            "用户选择": {
                "新洲区": 21,
                "黄陂区": 29,
                "江夏区": 60,
                "蔡甸区": 27,
                "汉南区": 3,
                "东西湖区": 34,
                "洪山区": 119,
                "青山区": 44,
                "武昌区": 174,
                "汉阳区": 221,
                "硚口区": 142,
                "江汉区": 275,
                "江岸区": 384,
                "市辖区": 0,
                "武汉市": 0
            }
        },
        "黄石市": {
            "定位": {
                "大冶市": 78,
                "阳新县": 15,
                "铁山区": 4,
                "下陆区": 38,
                "西塞山区": 9,
                "黄石港区": 24,
                "市辖区": 0,
                "黄石市": 0
            },
            "用户选择": {
                "大冶市": 82,
                "阳新县": 47,
                "铁山区": 83,
                "下陆区": 101,
                "西塞山区": 85,
                "黄石港区": 4361,
                "市辖区": 0,
                "黄石市": 0
            }
        },
        "十堰市": {
            "定位": {
                "丹江口市": 29,
                "房县": 12,
                "竹溪县": 8,
                "竹山县": 8,
                "郧西县": 19,
                "郧阳区": 21,
                "张湾区": 32,
                "茅箭区": 72,
                "市辖区": 0,
                "十堰市": 0
            },
            "用户选择": {
                "丹江口市": 28,
                "房县": 13,
                "竹溪县": 14,
                "竹山县": 34,
                "郧西县": 59,
                "郧阳区": 51,
                "张湾区": 48,
                "茅箭区": 85,
                "市辖区": 0,
                "十堰市": 0
            }
        },
        "宜昌市": {
            "定位": {
                "枝江市": 33,
                "当阳市": 31,
                "宜都市": 71,
                "五峰土家族自治县": 2,
                "长阳土家族自治县": 48,
                "秭归县": 39,
                "兴山县": 26,
                "远安县": 16,
                "夷陵区": 44,
                "猇亭区": 2,
                "点军区": 10,
                "伍家岗区": 56,
                "西陵区": 108,
                "市辖区": 0,
                "宜昌市": 0
            },
            "用户选择": {
                "枝江市": 34,
                "当阳市": 30,
                "宜都市": 69,
                "五峰土家族自治县": 2,
                "长阳土家族自治县": 51,
                "秭归县": 43,
                "兴山县": 31,
                "远安县": 41,
                "夷陵区": 135,
                "猇亭区": 57,
                "点军区": 20,
                "伍家岗区": 54,
                "西陵区": 129,
                "市辖区": 0,
                "宜昌市": 0
            }
        },
        "襄阳市": {
            "定位": {
                "宜城市": 28,
                "枣阳市": 48,
                "老河口市": 24,
                "保康县": 12,
                "谷城县": 21,
                "南漳县": 13,
                "襄州区": 46,
                "樊城区": 78,
                "襄城区": 27,
                "市辖区": 0,
                "襄阳市": 0
            },
            "用户选择": {
                "宜城市": 21,
                "枣阳市": 42,
                "老河口市": 29,
                "保康县": 44,
                "谷城县": 77,
                "南漳县": 20,
                "襄州区": 53,
                "樊城区": 70,
                "襄城区": 26,
                "市辖区": 0,
                "襄阳市": 0
            }
        },
        "鄂州市": {
            "定位": {
                "鄂城区": 44,
                "华容区": 11,
                "梁子湖区": 8,
                "市辖区": 0,
                "鄂州市": 0
            },
            "用户选择": {
                "鄂城区": 49,
                "华容区": 17,
                "梁子湖区": 12,
                "市辖区": 0,
                "鄂州市": 0
            }
        },
        "荆门市": {
            "定位": {
                "钟祥市": 36,
                "沙洋县": 23,
                "京山县": 0,
                "掇刀区": 34,
                "东宝区": 41,
                "市辖区": 0,
                "荆门市": 0
            },
            "用户选择": {
                "钟祥市": 39,
                "沙洋县": 31,
                "京山县": 27,
                "掇刀区": 28,
                "东宝区": 49,
                "市辖区": 0,
                "荆门市": 0
            }
        },
        "孝感市": {
            "定位": {
                "汉川市": 21,
                "安陆市": 27,
                "应城市": 9,
                "云梦县": 10,
                "大悟县": 15,
                "孝昌县": 4,
                "孝南区": 74,
                "市辖区": 0,
                "孝感市": 0
            },
            "用户选择": {
                "汉川市": 11,
                "安陆市": 22,
                "应城市": 15,
                "云梦县": 5,
                "大悟县": 12,
                "孝昌县": 4,
                "孝南区": 67,
                "市辖区": 0,
                "孝感市": 0
            }
        },
        "荆州市": {
            "定位": {
                "松滋市": 9,
                "洪湖市": 16,
                "石首市": 8,
                "江陵县": 13,
                "监利县": 34,
                "公安县": 10,
                "荆州区": 32,
                "沙市区": 39,
                "市辖区": 0,
                "荆州市": 0
            },
            "用户选择": {
                "松滋市": 8,
                "洪湖市": 10,
                "石首市": 10,
                "江陵县": 11,
                "监利县": 28,
                "公安县": 7,
                "荆州区": 27,
                "沙市区": 46,
                "市辖区": 0,
                "荆州市": 0
            }
        },
        "黄冈市": {
            "定位": {
                "武穴市": 15,
                "麻城市": 24,
                "黄梅县": 7,
                "蕲春县": 31,
                "浠水县": 19,
                "英山县": 11,
                "罗田县": 8,
                "红安县": 5,
                "团风县": 3,
                "黄州区": 31,
                "市辖区": 0,
                "黄冈市": 0
            },
            "用户选择": {
                "武穴市": 15,
                "麻城市": 19,
                "黄梅县": 10,
                "蕲春县": 20,
                "浠水县": 19,
                "英山县": 13,
                "罗田县": 3,
                "红安县": 13,
                "团风县": 2,
                "黄州区": 34,
                "市辖区": 0,
                "黄冈市": 0
            }
        },
        "咸宁市": {
            "定位": {
                "赤壁市": 56,
                "通山县": 23,
                "崇阳县": 15,
                "通城县": 19,
                "嘉鱼县": 19,
                "咸安区": 168,
                "市辖区": 0,
                "咸宁市": 0
            },
            "用户选择": {
                "赤壁市": 58,
                "通山县": 21,
                "崇阳县": 17,
                "通城县": 17,
                "嘉鱼县": 21,
                "咸安区": 186,
                "市辖区": 0,
                "咸宁市": 0
            }
        },
        "随州市": {
            "定位": {
                "广水市": 38,
                "随县": 22,
                "曾都区": 100,
                "市辖区": 0,
                "随州市": 0
            },
            "用户选择": {
                "广水市": 36,
                "随县": 16,
                "曾都区": 96,
                "市辖区": 0,
                "随州市": 0
            }
        },
        "恩施土家族苗族自治州": {
            "定位": {
                "鹤峰县": 16,
                "来凤县": 6,
                "咸丰县": 27,
                "宣恩县": 7,
                "巴东县": 11,
                "建始县": 5,
                "利川市": 15,
                "恩施市": 47,
                "恩施土家族苗族自治州": 0
            },
            "用户选择": {
                "鹤峰县": 14,
                "来凤县": 5,
                "咸丰县": 31,
                "宣恩县": 7,
                "巴东县": 5,
                "建始县": 6,
                "利川市": 11,
                "恩施市": 48,
                "恩施土家族苗族自治州": 0
            }
        },
        "省直辖县级行政区划": {
            "定位": {
                "神农架林区": 9,
                "天门市": 96,
                "潜江市": 63,
                "仙桃市": 57,
                "省直辖县级行政区划": 0
            },
            "用户选择": {
                "神农架林区": 15,
                "天门市": 100,
                "潜江市": 67,
                "仙桃市": 56,
                "省直辖县级行政区划": 0
            }
        }
    }
     * */
}
