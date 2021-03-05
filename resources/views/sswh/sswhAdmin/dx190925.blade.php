@extends('layouts.adminlte.simple-a')
<?php
$statusNow = request()->input('status', '');
$prizeArr = [
    0 => '未中奖',
    10 => '10元话费',
    30 => '<span style="color: #c26629">30元话费</span>',
    50 => '<span style="color: #39cccc">50元话费</span>',
    100 => '<span style="color: #e3342f">100元话费</span>'
];
$ststusArr = [
    0 => '未抽奖',
    1 => '已中奖',
    2 => '未中奖'
];
$subscribe = [
    0 =>'未关注',
    1 =>'已关注'
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
                <div class="col-md-4 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/dx190925/index')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入昵称或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择是否中奖</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未抽奖</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已中奖</option>
                                <option {{($statusNow =='2')?'selected':''}} value="2">未中奖</option>
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
                                    <th style="text-align: center;">关注公众号</th>
                                    <th style="text-align: center;">区域</th>
{{--                                    <th style="text-align: center;">经纬度</th>--}}
                                    <th style="text-align: center;">是否抽奖</th>
                                    <th style="text-align: center;">奖品</th>
                                    <th style="text-align: center;">手机号码</th>
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
                                        <td align="center">{{$subscribe[$user['subscribe']]}}</td>
                                        <td align="center">{{$user['virtual_address_str']}}</td>
{{--                                        <td align="center">{{$user['location']}}</td>--}}
                                        <td align="center">{{$ststusArr[$user['status']]}}</td>
                                        <td align="center">{!! $prizeArr[$user['prize']]  !!}</td>
                                        <td align="center">{{$user['phone']}}</td>
                                        <td align="center">{{$user['prize_at']}}</td>
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
