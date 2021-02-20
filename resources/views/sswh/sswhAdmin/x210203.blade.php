@extends('layouts.adminlte.simple-d')

@section('title', $title)

@php
    $itemNow = request()->input('item_id', '');
@endphp
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

        .tb-img {
            width: 110px;
            height: 85px;
            display: block;
            padding: 4px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
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
                    <form class="form-inline" method="get" action="{{url('vlvl/x210203/index')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrmobile"
                                   value="{{(request()->has('nameOrmobile')&&(request()->nameOrmobile!=''))?request()->nameOrmobile:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="item_id" class="form-control">
                                <option value="">请选择项目</option>
                                <option {{($itemNow =='1')?'selected':''}} value="1">东湖金茂府</option>
                                <option {{($itemNow =='2')?'selected':''}} value="2">滨江金茂府</option>
                                <option {{($itemNow =='3')?'selected':''}} value="3">华发阳逻金茂逸墅</option>
                                <option {{($itemNow =='4')?'selected':''}} value="4">阳逻金茂逸墅</option>
                                <option {{($itemNow =='5')?'selected':''}} value="5">阳逻金茂悦</option>
                                <option {{($itemNow =='6')?'selected':''}} value="6">方岛金茂智慧科学城</option>
                                <option {{($itemNow =='7')?'selected':''}} value="7">武汉国际社区</option>
                                <option {{($itemNow =='8')?'selected':''}} value="8">建发金茂玺悦</option>
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
                                    <th style="text-align: center;">项目</th>
                                    <th style="text-align: center;">房号</th>
                                    <th style="text-align: center;">参与人数</th>
                                    <th style="text-align: center;">报名时间</th>
                                    <th style="text-align: center;">参与时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65"
                                                 src="{{strlen($user['avatar']) > 20?$user['avatar']:'https://wx.sanshanwenhua.com/statics/images/common/timg.jpg'}}"
                                                 alt="">
                                            <div class="nickname">{{$user['nickname']}}</div>
                                        </td>
                                        <td>{{$user['name']}}</td>
                                        <td>{{$user['mobile']}}</td>
                                        <td>{{$user['item']}}</td>
                                        <td>{{$user['room_no']}}</td>
                                        <td>{{$user['num']}}</td>
                                        <td>{{$user['sign_up_at']}}</td>
                                        <td>{{$user['updated_at']}}</td>
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
