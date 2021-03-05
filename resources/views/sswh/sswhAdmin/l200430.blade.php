@extends('layouts.adminlte.simple-a')
<?php
$itemNow = request()->input('item', '');
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
                <div class="col-md-4 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/l200430/index')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="item" class="form-control">
                                <option value="">请选择项目</option>
                                <option {{($itemNow =='0')?'selected':''}} value="0">山湖对弈</option>
                                <option {{($itemNow =='1')?'selected':''}} value="1">环湖畅跑</option>
                                <option {{($itemNow =='2')?'selected':''}} value="2">野趣摄影</option>
                                <option {{($itemNow =='3')?'selected':''}} value="3">儿童乐园</option>
                                <option {{($itemNow =='4')?'selected':''}} value="4">纤手护理</option>
                                <option {{($itemNow =='5')?'selected':''}} value="5">研习花艺</option>
                                <option {{($itemNow =='6')?'selected':''}} value="6">风筝游趣</option>
                                <option {{($itemNow =='7')?'selected':''}} value="7">露营野餐</option>
                                <option {{($itemNow =='8')?'selected':''}} value="8">湖边品茗</option>
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
                                    <th style="text-align: center;">姓名</th>
                                    <th style="text-align: center;">电话</th>
                                    <th style="text-align: center;">项目名称</th>
                                    <th style="text-align: center;">身份证</th>
                                    <th style="text-align: center;">预约人数</th>
                                    <th style="text-align: center;">预约时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>{{$user['truename']}}</td>
                                        <td>{{$user['phone']}}</td>
                                        <td>{{$user['item_name']}}</td>
                                        <td align="center">{{$user['card']}}</td>
                                        <td align="center">{{$user['num']}}</td>
                                        <td align="center">{{$user['register_at']}}</td>
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
