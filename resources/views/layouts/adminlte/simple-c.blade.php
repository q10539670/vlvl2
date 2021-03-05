<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

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
    @yield('css')
</head>
<body class="skin-blue layout-top-nav">
<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="javascript:void(0)" class="navbar-brand"><b>@yield('title')</b></a>
                </div>
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                    @yield('nav')
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
                                <span class="info-box-number">{{ceil($redisShareData['view']*3.2)??0}}</span>
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
                                <span class="info-box-text">分享给朋友</span>
                                <span class="info-box-number">{{ceil($redisShareData['firend']*3.2)??0}}</span>
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
                                <span class="info-box-text">分享到朋友圈</span>
                                <span class="info-box-number">{{ceil($redisShareData['tl']*3.2)??0}}</span>
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
                                <span class="info-box-number">{{(ceil($redisShareData['firend']*3.2)??0)+(ceil($redisShareData['tl']*3.2)??0)}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        @yield('content')
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
@yield('js')
</body>
</html>
