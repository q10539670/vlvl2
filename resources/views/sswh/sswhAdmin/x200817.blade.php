@extends('layouts.adminlte.simple-a')
<?php
$statusNow = request()->input('status', '');
$prizesNow = request()->input('prize_id', '');
$siteNow = request()->input('site', '');
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
                <div class="col-md-2 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x200817/index')}}">
                        <div class="form-group">
                            <select name="site" class="form-control">
                                <option value="">请选择活动场次</option>
                                <option {{($siteNow =='1-1')?'selected':''}} value="1-1">场地一/第一场</option>
                                <option {{($siteNow =='1-2')?'selected':''}} value="1-2">场地一/第二场</option>
                                <option {{($siteNow =='2-1')?'selected':''}} value="2-1">场地二/第一场</option>
                                <option {{($siteNow =='2-2')?'selected':''}} value="2-2">场地二/第二场</option>
                            </select>
                        </div>

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
                                <option {{($statusNow =='1')?'selected':''}} value="1">已中奖</option>
                                <option {{($statusNow =='3')?'selected':''}} value="3">未中奖</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="prize_id" class="form-control">
                                <option value="">请选择中奖奖品</option>
                                <option {{($prizesNow =='1')?'selected':''}} value="1">一等奖</option>
                                <option {{($prizesNow =='2')?'selected':''}} value="2">二等奖</option>
                                <option {{($prizesNow =='3')?'selected':''}} value="3">三等奖</option>
                                <option {{($prizesNow =='4')?'selected':''}} value="4">幸运奖</option>
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
                                    <th style="text-align: center;">电话</th>
                                    <th style="text-align: center;">房号</th>
                                    <th style="text-align: center;">中奖奖品</th>
                                    <th style="text-align: center;">中奖轮数</th>
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
                                        <td align="center">{{$user['room_no']}}</td>
                                        <td align="center">{{$user['prize']}}</td>
                                        @if($user['round'] == 0)
                                            <td align="center"></td>
                                        @else
                                            <td align="center">第{{$user['round']}}轮</td>
                                        @endif
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
