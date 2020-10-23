@extends('layouts.adminlte.simple-d')
<?php
$statusNow = request()->input('status', '');
$moneyNow = request()->input('money', '');
$moneyLabel = [
    -1 => '<span class="badge bg-gary">0</span>',
    0 => '--',
    100 => '<span class="badge bg-teal">1.00</span>',
    188 => '<span class="badge bg-teal">1.88</span>',
    666 => '<span class="badge bg-aqua">6.66</span>',
    888 => '<span class="badge bg-blue">8.88</span>',
    1288 => '<span class="badge bg-green">12.88</span>',
    6660 => '<span class="badge bg-info">66.60</span>',
    8880 => '<span class="badge bg-yellow">88.80</span>',
];
$statusLabel = [
    0 => '未抽奖',
    10 => '中奖未兑奖',
    11 => '中奖已兑奖',
    12 => '中奖已过期',
    2 => '未中奖',
    3 => '红包发送失败'
];

//$str = '[{id:11,time:"11:30-12:30"},{id:12,time:"12:30-13:30"},{id:13,time:"13:30-14:30"},{id:14,time:"14:30-15:30"},{id:15,time:"15:30-16:30"},{id:16,time:"16:30-17:30"},{id:17,time:"17:30-18:30"},{id:18,time:"18:30-19:30"},{id:19,time:"19:30-20:00"}]';
//$timeInfo = json_decode($str,true);
$timeInfo = [
  ["id"=>11,"time"=>"11:30-12:30"],
  ["id"=>12,"time"=>"12:30-13:30"],
  ["id"=>13,"time"=>"13:30-14:30"],
  ["id"=>14,"time"=>"14:30-15:30"],
  ["id"=>15,"time"=>"15:30-16:30"],
  ["id"=>16,"time"=>"16:30-17:30"],
  ["id"=>17,"time"=>"17:30-18:30"],
  ["id"=>18,"time"=>"18:30-19:30"],
  ["id"=>19,"time"=>"19:30-20:00"],
];

//dd($str,$timeInfo);
?>
@section('title', $title)

@section('css')
    <link rel="stylesheet" href="{{asset('/vlvl/layui/css/modules/layer/default/layer.css')}}">
    <style>
        .ss-avatar {
            border: 1px solid silver;
            border-radius: 3px;
            padding: 5px;
            width: 75px;
            height: 75px;
        }

        #ss-tb th {
            vertical-align: middle;
        }

        #ss-tb td {
            vertical-align: middle;
        }

        #ss-tb .time-td {
            width: 90px;
        }

        .tdr-avatar {
            max-width: 100%;
            padding: 4px;
            border-radius: 4px;
            border: 1px solid silver;
            margin-bottom: 4px;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-6 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x201013a/index')}}">

                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">查询</button>
                        </div>
                        <div class="form-group">
                            <a href="{!! $exportUrl !!}" class="btn btn-default form-control">下载</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /.box-header -->
        <div class="box-body" style="padding-top: 0;">
            <div id="" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" id="ss-tb">
                                <thead>
                                <tr>
                                    <th width="35" align="center">序号</th>
                                    <th style="text-align: center;">头像/昵称</th>
                                    <th style="text-align: center;">姓名</th>
                                    <th style="text-align: center;">手机号码</th>
                                    <th style="text-align: center;">预约人数</th>
                                    <th style="text-align: center;">预约日期</th>
                                    <th style="text-align: center;">预约时间</th>
                                    <th style="text-align: center;">参与时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65" src="{{$user->user->avatar}}" alt="">
                                            <div class="nickname">{{$user->user->nickname}}</div>
                                        </td>
                                        <td align="center">{{$user['name']}}</td>
                                        <td align="center">{{$user['phone']}}</td>
                                        <td align="center">{{$user['num']}}</td>
                                        <td align="center">{{$user['reserve_date']}}</td>
                                        <td align="center">
                                            @foreach($timeInfo as $v)
                                                @if($user['reserve_time'] == $v['id'])
                                                {{$v['time']}}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td align="center">{{$user['created_at']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{$paginator->appends(request()->all())->links('vendor.pagination.simple-b-page')}}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
