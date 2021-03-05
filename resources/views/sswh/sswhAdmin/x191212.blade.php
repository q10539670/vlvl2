<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>三山文化|12月份抽奖</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{--<link rel="stylesheet" href="{{asset('css/app.css')}}">--}}
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vlvl/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('vlvl/css/dataTables.bootstrap.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('vlvl/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/dist/css/skins/skin-blue.min.css')}}">

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
</head>
<?php
$statusNow = request()->input('status', '');
?>
<body class="skin-blue layout-top-nav">
<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="javascript:void(0)" class="navbar-brand"><b>三山文化|12月份抽奖</b></a>
                </div>
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
{{--                    @yield('nav')--}}
                    <!-- /.messages-menu -->
                        {{--<li class="">--}}
                        {{--<!-- Menu toggle button -->--}}
                        {{--<a href="#"  title="导出EXCEL并下载">--}}
                        {{--<i class="fa fa-save"> 下载</i>--}}
                        {{--<span class="label label-success"></span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </div>
            <!-- /.container-fluid -->
        </nav>
    </header>
    <!-- Full Width Column -->
    <div class="content-wrapper" style="min-height: 848px;">
        <div class="container">
            <!-- Content Header (Page header) -->

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-flickr"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">浏览量</span>
                                <span class="info-box-number">36038</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-weixin"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">参与人数</span>
                                <span class="info-box-number">12149</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix visible-sm-block"></div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-share-alt"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">注册人数</span>
                                <span class="info-box-number">10392</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-share-alt-square"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">分享总数</span>
                                <span class="info-box-number">2827</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="row">
                                    <div class="col-md-4 col-sm-2">
                                        <div class="form-group">
                                            <h3 class="box-title">用户列表</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                                        <form class="form-inline" method="get" action="{{url('vlvl/x191212/index')}}">

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
                                                        <th style="text-align: center;">姓名</th>
                                                        <th style="text-align: center;">手机号码</th>
                                                        <th style="text-align: center;">邮寄地址</th>
                                                        <th style="text-align: center;">奖品</th>
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
                                                            <td align="center">{{$user['address']}}</td>
                                                            <td align="center">{{$user['prize']}}</td>
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
                    </div>
                </div>

                <!-- /.box -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.container -->
    </div>
    {{--<footer class="main-footer">--}}
    {{--<div class="container">--}}
    {{--<div class="pull-right hidden-xs">--}}
    {{--<b>Version</b> 2.3.7--}}
    {{--</div>--}}
    {{--<strong>Copyright © 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights--}}
    {{--reserved.--}}
    {{--</div>--}}
    {{--</footer>--}}
</div>

{{--<script src="{{ asset('js/app.js') }}"></script>--}}
<script src="{{asset('vlvl/adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('vlvl/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('vlvl/adminlte/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('vlvl/layui/layui.all.js')}}"></script>
<script>
    /*处理分页跳转*/
    $('#page-jump').click(function () {
        var pageIndex = $('#page-index').val();
        var pageLis = $('#pagination-li-s li');
        var maxPage = parseInt(pageLis.eq(pageLis.length - 3).text());
        if (!pageIndex || parseInt(pageIndex) < 0 || pageIndex > maxPage) {
            return false;
        }
        var baseUrl = location.hostname + location.pathname;
        if (/\?/.test(location.href)) {
            if (/page=\d*/.test(location.href)) {
                location.href = location.href.replace(/page=\d*/, 'page=' + pageIndex);
            } else {
                location.href = location.href + '&page=' + pageIndex;
            }
        } else {
            location.href = location.href + '?page=' + pageIndex;
        }
    })

</script>
</body>
</html>
