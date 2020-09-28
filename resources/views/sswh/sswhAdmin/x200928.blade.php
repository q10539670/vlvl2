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
    2 => '未中奖'
];
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
                <div class="col-md-3 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-7 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x200928/index')}}">

                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择是否中奖</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未抽奖</option>
                                <option {{($statusNow =='10')?'selected':''}} value="10">中奖未兑奖</option>
                                <option {{($statusNow =='11')?'selected':''}} value="11">中奖已兑奖</option>
                                <option {{($statusNow =='12')?'selected':''}} value="12">中奖已过期</option>
                                <option {{($statusNow =='2')?'selected':''}} value="2">未中奖</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="money" class="form-control">
                                <option value="">请选择中奖金额</option>
                                <option {{($moneyNow =='188')?'selected':''}}  value="188">1.88元</option>
                                <option {{($moneyNow =='666')?'selected':''}}  value="666">6.66元</option>
                                <option {{($moneyNow =='888')?'selected':''}}  value="888">8.88元</option>
                                <option {{($moneyNow =='1288')?'selected':''}} value="1288">12.88元</option>
                                <option {{($moneyNow =='6660')?'selected':''}} value="6660">66.60元</option>
                                <option {{($moneyNow =='8880')?'selected':''}} value="8880">88.80元</option>
                            </select>
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
                                    <th style="text-align: center;">状态</th>
                                    <th style="text-align: center;">验证码</th>
                                    <th style="text-align: center;">中奖</br>金额(元)</th>
                                    <th style="text-align: center;">中奖时间</th>
                                    <th style="text-align: center;">参与时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65" src="{{$user['avatar']}}" alt="">
                                            <div class="nickname">{{$user['nickname']}}</div>
                                        </td>
                                        <td align="center">{{$user['name']}}</td>
                                        <td align="center">{{$user['phone']}}</td>
                                        <td align="center">{{$statusLabel[$user['status']]??'未识别状态'}}</td>
                                        <td align="center">{{$user['v_code']}}</td>
                                        <td align="center">{!! $moneyLabel[$user['money']] !!}</td>
                                        <td align="center">{{$user['prized_at']}}</td>
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
