@extends('layouts.adminlte.simple-a')
<?php
$statusNow = request()->input('status', '');
$moneyNow = request()->input('prize', '');
$moneyLabel = [
    -1 => '<span class="badge bg-gary">0</span>',
    0 => '--',
    100 => '<span class="badge bg-teal">1.00</span>',
    168 => '<span class="badge bg-aqua">1.68</span>',
    880 => '<span class="badge bg-yellow">8.80</span>',
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
                    <form class="form-inline" method="get" action="{{url('vlvl/x200429/index')}}">
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择是否答题</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未答题</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已答题</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="prize" class="form-control">
                                <option value="">请选择中奖金额</option>
                                <option {{($moneyNow =='0')?'selected':''}}  value="0">未中奖</option>
                                <option {{($moneyNow =='100')?'selected':''}} value="100">1.00元</option>
                                <option {{($moneyNow =='168')?'selected':''}} value="168">1.68元</option>
                                <option {{($moneyNow =='880')?'selected':''}} value="880">8.80元</option>
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
                                    <th style="text-align: center;">昵称</th>
                                    <th style="text-align: center;">中奖金额</th>
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
