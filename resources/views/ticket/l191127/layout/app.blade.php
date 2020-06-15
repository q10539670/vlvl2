<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>三山文化|In小时光面馆</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/bower_components/Ionicons/css/ionicons.min.css')}}">

    <link rel="stylesheet" href="{{asset('vlvl/adminlte/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/plugins/datatables/dataTables.bootstrap.css')}}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{asset('vlvl/adminlte/dist/css/skins/skin-blue.min.css')}}">
    {{--<link rel="stylesheet" href="{{asset('vlvl/layui/css/layui.css')}}">--}}
    <link rel="stylesheet" href="{{asset('vlvl/layui/css/modules/layer/default/layer.css')}}">
    {{--<link rel="stylesheet" href="{{ asset('vlvl/viewerjs/viewer.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('vlvl/css/app.css') }}">
    <link rel="stylesheet" href="{{asset('vlvl/css/admin.css')}}">
@yield('css')
@yield('style')

<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini ">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>汤</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>汤达人小票管理</b>后台</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav" id="header_status">
                    <li>
                        <a href="javascript:void(0)"
                           class="redpack_off {{$serviceRedpackStatus ==1?'hidden':''}}">红包已关闭</a>
                        <a href="javascript:void(0)"
                           class="redpack_on {{$serviceRedpackStatus ==0?'hidden':''}}">红包已开启</a>
                    </li>
                    <li class="dropdown tasks-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-jpy"></i>
                            <span class="label label-success">{{$repackCount['totalMoney']/100}}</span>
                            红包
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">
                                已发送红包发送数量 [今日/昨天](个)
                            </li>
                            <li>
                                <!-- Inner menu: contains the tasks -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                1 元红包 [今日/昨天](个)
                                                <span class="label label-primary pull-right">
                                                    {{$repackCount['today']['100'].'/'.$repackCount['yesterday']['100']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                2 元红包 [今日/昨天](个)
                                                <span class="label label-primary pull-right">
                                                    {{$repackCount['today']['200'].'/'.$repackCount['yesterday']['200']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                5 元红包 [今日/昨天](个)
                                                <span class="label label-primary pull-right">
                                                    {{$repackCount['today']['500'].'/'.$repackCount['yesterday']['500']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <li class="footer">
                                        <div class="clearfix">
                                            <div style="margin-left:8px;line-height: 45px;color:grey;float: left;">
                                                累计金额[今日/昨日](元)
                                            </div>
                                            <div style="float: right;line-height: 45px;height: 45px;">
                                                <div class="label label-info" style="margin-left: 5px;margin-right: 5px;">
                                                    {{($repackCount['today']['totalMoney']/100).'/'.($repackCount['yesterday']['totalMoney']/100)}}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown tasks-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">{{$checkCount['allNotChecked']}}</span>
                            任务
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">小票详情</li>
                            <li>
                                <!-- Inner menu: contains the tasks -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                小票总数 [今日/昨天]
                                                <span class="label label-primary pull-right">
                                                    {{$checkCount['today']['total'].'/'.$checkCount['yesterday']['total']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                已审核数量 [今日/昨天]
                                                <span class="label label-primary pull-right">
                                                    {{$checkCount['today']['checked'].'/'.$checkCount['yesterday']['checked']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                待审核数量 [今日/昨天]
                                                <span class="label label-primary pull-right">
                                                    {{$checkCount['today']['notChecked'].'/'.$checkCount['yesterday']['notChecked']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                总待审核数量
                                                <span class="label label-primary pull-right">
                                                    {{$checkCount['allNotChecked']}}
                                                </span>
                                            </h3>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                {{--<li><!-- Task item -->--}}
                                {{--<a href="#">--}}
                                {{--<!-- Task title and progress text -->--}}
                                {{--<h3>--}}
                                {{--Design some buttons--}}
                                {{--<small class="pull-right">20%</small>--}}
                                {{--</h3>--}}
                                {{--<!-- The progress bar -->--}}
                                {{--<div class="progress xs">--}}
                                {{--<!-- Change the css width attribute to simulate progress -->--}}
                                {{--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"--}}
                                {{--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
                                {{--<span class="sr-only">20% Complete</span>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</a>--}}
                                {{--</li>--}}
                                <!-- end task item -->
                                </ul>
                            </li>
                            {{--<li class="footer">--}}
                            {{--<a href="#">View all tasks</a>--}}
                            {{--</li>--}}
                        </ul>
                    </li>
                    <!-- Messages: style can be found in dropdown.less-->
                    <li class="dropdown messages-menu">
                        <!-- Menu toggle button -->
                        <a onclick="
                                // event.preventDefault();
                                if(!confirm('您确认注销吗？'))return false;
                                document.getElementById('logout-form').submit();"
                           href="javascript:void(0);">
                            注 销
                        </a>
                        <form id="logout-form" class="hidden" action="{{route('l191127.logout')}}"
                              method="POST">@csrf</form>
                    </li>
                    <!-- /.messages-menu -->
                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

        @include("ticket.l191127.layout.partials._sidebar")
        <!-- sidebar end -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('content-header')

        <section class="content container-fluid">
        @include("ticket.l191127.layout.partials._flash")
        <!--------------------------
              | Your Page Content Here |
              -------------------------->
            @yield('content')
        </section>
        <!-- /.content -->
    </div>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset('vlvl/js/app.js') }}"></script>
<!-- jQuery 3 -->
{{--<script src="{{asset('vlvl/adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>--}}
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('vlvl/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vlvl/adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('vlvl/adminlte/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('vlvl/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('vlvl/js/daterangepicker-config.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('vlvl/adminlte/dist/js/adminlte.min.js')}}"></script>
{{--<script src="{{asset('vlvl/layui/layui.all.js')}}"></script>--}}
<script src="{{asset('vlvl/layui/lay/modules/layer.js')}}"></script>
{{--<script type="text/javascript" src="{{asset('vlvlviewerjs/viewer.min.js')}}"></script>--}}
<script src="{{asset('vlvl/js/admin.js')}}"></script>
<script>
    $('input').iCheck({
        labelHover: false,
        cursor: true,
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
    });
    $('#header_status').on('click', '.redpack_off,.redpack_on', function (e) {
        var status;
        if (e.currentTarget.className == 'redpack_off') {
            status = 0;
        } else if (e.currentTarget.className == 'redpack_on') {
            status = 1;
        }
        loading = layer.load(1, {
            shade: [0.1, '#fff'] //0.1透明度的白色背景
        });
        $.ajax({
            url: '{{route("l191127.service.redpack")}}',
            type: 'post',
            data: {status: status},
            success: function (res) {
                layer.close(loading); //关闭loading
                layer.msg(res.message, {time: 2000});
                console.log(res);
                if (res.data.status == 1) {
                    $('.redpack_off').addClass('hidden');
                    $('.redpack_on').removeClass('hidden');
                } else if (res.data.status == 0) {
                    $('.redpack_on').addClass('hidden');
                    $('.redpack_off').removeClass('hidden');
                }
            }
        })
    })
</script>
@yield('js')

</body>
</html>
