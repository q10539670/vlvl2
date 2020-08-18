<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>金地华中·第六届纳凉音乐节场地二抽奖</title>

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
                    <a href="javascript:void(0)" class="navbar-brand"><b>金地华中·第六届纳凉音乐节抽奖<span style="color: #E8BF6A">场地二</span></b></a>
                </div>
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

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
                <div class="alert alert-info alert-dismissible">
                    <h4><i class="icon fa fa-commenting"></i>提示</h4>
                    <input type="hidden" value="{{$round}}" id="round">
                    @if($round == 0)
                    <span id="alert" style="text-align: center">当前抽奖已停止</span>
                    @else
                        <span id="alert" style="text-align: center">当前为场地二第{{$round}}轮抽奖</span>
                    @endif
                        <br><br>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" onclick="round(this,1,1)" class="btn btn-block btn-primary">第一轮幸运奖(5名)</button>
                        <button type="button" onclick="round(this,2,1)" class="btn btn-block btn-primary">第二轮幸运奖(5名)</button>
                        <button type="button" onclick="round(this,3,1)" class="btn btn-block btn-primary">第三轮三等奖(3名)</button>
                        <button type="button" onclick="round(this,4,1)" class="btn btn-block btn-primary">第四轮二等奖(2名)</button>
                        <button type="button" onclick="round(this,5,1)" class="btn btn-block btn-primary">第五轮一等奖(1名)</button>
                        <button type="button" onclick="round(this,0,0)" class="btn btn-block btn-danger">停止</button>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12" id="prizes">

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
        {{--window.onload = function () {--}}
        {{--    getPrize();--}}
        {{--    // getPrizes()--}}
        {{--};--}}
        {{--var ii = 1;--}}
        {{--function getPrize() {--}}
        {{--    setTimeout(getPrize, 1200);--}}
        {{--    $.ajax({--}}
        {{--        url: '{{url('vlvl/api/sswh/x200106r/get_prize')}}/' + $('#round').val()+'?'+ii,--}}
        {{--        type: 'get',--}}
        {{--        success: function (num) {--}}
        {{--            console.log(num);--}}
        {{--            $('#prize').html('第'+$('#round').val()+'波剩余奖品数:'+num);--}}
        {{--            ii++;--}}
        {{--        },--}}
        {{--        error:function(){--}}
        {{--            ii++;--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
        {{--function getPrizes() {--}}
        {{--    setTimeout(getPrizes, 500);--}}
        {{--    $("#prizes").empty();--}}
        {{--    html = '';--}}
        {{--    $.ajax({--}}
        {{--        url: '{{url('vlvl/x200106r/prizes')}}/'+$('#round').val()+'?'+ii,--}}
        {{--        type: 'get',--}}
        {{--        success: function (res) {--}}
        {{--            console.log(res);--}}
        {{--            if(res) {--}}
        {{--                $.each (res, function (index, value) {--}}
        {{--                    html += "<tr align=\"center\"><td>" + value.nickname + "</td><td>" + value.prize + "</td></tr>";--}}
        {{--                });--}}
        {{--            }--}}
        {{--            $("#prizes").append(html);--}}
        {{--            ii++;--}}
        {{--        },--}}
        {{--        error:function(){--}}
        {{--            ii++;--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
    function round(obj, round,status) {
        var msg = '';
        if (round == 0) {
            msg = '确定停止场地二抽奖吗?'
        } else {
            msg = '确定开启场地二第' + round + '波抽奖吗?'
        }
        layer.confirm(msg, function () {
            $.ajax({
                url: '{{url('vlvl/x200817/set_site2round')}}',
                type: 'post',
                data: {'round': round, 'status':status, _token: '{{csrf_token()}}'},
                success: function (res) {
                    if (res == '停止') {
                        layer.msg('停止抽奖');
                        $('#alert').html('当前抽奖未开始');
                    } else {
                        layer.msg('开启场地二第' + round + '波抽奖成功');
                        $('#alert').html('当前为场地二第' + round + '波抽奖');
                        $('#round').val(round)
                    }
                }
            })
        })
    }

</script>
</body>
</html>
