@extends('layouts.adminlte.simple-d')
<?php
$companyLabel = [1 => '武汉生物制品研究所有限责任公司', 2 => '国药集团武汉血制公司', 3=>'武汉中生毓晋生物公司'];
$typeLabel = $typeLabel = [1 => '毛坯约8350元/平', 2 => '装修约8850元/平(装修标准市场价格2000元/平)', ];;
$houseLabel = [1 => '99㎡', 2 => '108㎡',3=>'115㎡',4=>'125㎡',5=> '133㎡'];
$zigeLabel = [1 => '有', 2 => '无'];
$statusNow = request()->input('status', '');
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
                <div class="col-md-5 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x201216/index')}}">

                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择是否报名</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未报名</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已报名</option>
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
                                    <th style="text-align: center;">所属公司</th>
                                    <th style="text-align: center;">购买资格</th>
                                    <th style="text-align: center;">意向房源</th>
                                    <th style="text-align: center;">意向户型</th>
                                    <th style="text-align: center;">登记时间</th>
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
                                        <td align="center">{{$user['mobile']}}</td>
                                        <td align="center">{{$companyLabel[$user['company']] ?? ''}}</td>
                                        <td align="center">{{$zigeLabel[$user['zige']] ?? ''}}</td>
                                        <td align="center">{{$typeLabel[$user['type']] ?? ''}}</td>
                                        <td align="center">{{$houseLabel[$user['house']] ?? ''}}</td>
                                        <td align="center">{{$user['updated_at']}}</td>
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
