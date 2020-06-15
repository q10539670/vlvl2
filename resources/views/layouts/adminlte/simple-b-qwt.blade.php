<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>三山文化|@yield('title')</title>

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
    <style>
        .count-warp {
            background-color: #fff;
            margin-bottom: 5px;
        }

        .count-warp-title {
            font-size: 14px;
            padding: 3px 8px;
            color: #fff;
            display: inline-block;
            background-color: #0a628f;
        }

        .count-warp-content {
            font-size: 18px;
            font-weight: bold;
            display: inline-block;
            float: right;
            margin-right: 8px;
        }
    </style>
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
            {{--<div class="col-sm-12 col-lg-8 col-lg-offset-2">--}}
            <section class="content">
                <div style="margin-bottom: 10px;">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">浏览量 :</div>
                                <div class="count-warp-content">{{$countData['viewNum']??0}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">参与人数 :</div>
                                <div class="count-warp-content">{{$countData['userNum']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">注册人数 :</div>
                                <div class="count-warp-content">{{$countData['registerNum']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">统计中奖率 :</div>
                                <div class="count-warp-content">{{$countData['zjlv']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">总金额 :</div>
                                <div class="count-warp-content">{{$countData['totalMoney']}}<span
                                            style="font-size:16px;font-weight: normal;">元</span></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">剩余金额 :</div>
                                <div class="count-warp-content">{{$countData['totalMoney']-$countData['prizeMoneyNumHubei']-$countData['prizeMoneyNumOther']}}
                                    <span style="font-size:16px;font-weight: normal;">元</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">3元红包个数 :</div>
                                <div class="count-warp-content">{{$countData['fiveMoneyHubei']+$countData['fiveMoneyOther']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">2元红包个数 :</div>
                                <div class="count-warp-content">{{$countData['twoMoneyHubei']+$countData['twoMoneyOther']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">1元红包个数 :</div>
                                <div class="count-warp-content">{{$countData['oneMoneyHubei']+$countData['oneMoneyOther']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">未中奖个数 :</div>
                                <div class="count-warp-content">{{$countData['zeroMoneyHubei']+$countData['zeroMoneyOther']}}</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">红包总数 :</div>
                                <div class="count-warp-content">{{$countData['totalRedpackNum']}}<span
                                            style="font-size:16px;font-weight: normal;">个</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="count-warp">
                                <div class="count-warp-title">剩余红包 :</div>
                                <div class="count-warp-content">{{$countData['totalRedpackNum']-$countData['prizeUserNumHubei']-$countData['prizeUserNumOther']}}
                                    <span style="font-size:16px;font-weight: normal;">个</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="count-warp">
                                <div class="count-warp-title">已发红包金额(全国/湖北)(元) :</div>
                                <div class="count-warp-content">{{($countData['prizeMoneyNumHubei']+$countData['prizeMoneyNumOther']).'/'.$countData['prizeMoneyNumHubei']}}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="count-warp">
                                <div class="count-warp-title">已发红包个数(全国/湖北) :</div>
                                <div class="count-warp-content">{{($countData['prizeUserNumHubei']+$countData['prizeUserNumOther']).'/'.$countData['prizeUserNumHubei']}}</div>
                            </div>
                        </div>
                        @if(isset($countData['zjlv2']))
                            <div class="col-md-4">
                                <div class="count-warp">
                                    <div class="count-warp-title">当前中奖率(湖北/其他地区) :</div>
                                    <div class="count-warp-content">{{($countData['zjlv2']['hubei_str'].'/'.$countData['zjlv2']['other_str'])}}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        @yield('content')
                    </div>
                </div>

                <!-- /.box -->
            </section>
        {{--</div>--}}
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
