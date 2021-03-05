@extends('ticket.l191127.layout.app')

@section('style')
    <style>
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

        #ticket-tb th {
            vertical-align: middle;
        }

        #ticket-tb td {
            vertical-align: middle;
        }

        .tongguo {
            margin-bottom: 8px;
        }

        .success-discrible {
            width: 245px;
            background: #f0fff0;
            padding: 5px;
            border-radius: 4px;
        }

        .tdr-avatar {
            max-width: 100%;
            padding: 4px;
            border-radius: 4px;
            border: 1px solid silver;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .p-describle {
            width: 215px;
            white-space: pre-wrap;
        }

        .finish {
            margin-bottom: 8px;
        }

        .check_title {
            background-color: burlywood;
            padding: 3px;
            color: currentColor;
        }

        .check_error {
            margin-top: 5px;
            background-color: silver;
            padding: 5px;
            border-radius: 3px;
            vertical-align: middle;
        }

        .check_details {
            width: 180px;
        }

        .location {
            text-align: center;
            width: 90px;
            white-space: pre-wrap;
        }
    </style>
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            小票列表
            {{--            <small>进入黑名单的用户上传的小票将不会显示在以下表格</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="">小票管理</li>
            <li class="active">小票列表</li>
        </ol>
    </section>
@endsection
{{--$imgPrefix = env('APP_URL').'/storage2/';--}}
@php
    $imgPrefix = 'https://wx.sanshanwenhua.com/vlvl/storage2/';
    $userStatus = [
        1=>'<span class="label label-info">正常</span>',
        2=>'<span class="label label-danger">禁用</span>',
    ];
    $checkStatusLabel = [
        0 => '<span class="label label-default">未审核</span>',
        10 => '<span class="label label-warning">审核中...</span>',
        11 => '<span class="label label-success">审核通过</span>',
        12 => '<span class="label label-danger">审核不通过</span>',
        13 => '<span class="label label-danger">【审核失败】</span>',
        20 => '<span class="label label-success">审核通过</span><br/><br/><span style="margin-top:5px;" class="label label-warning">红包发送中...</span>',
        21 => '<span class="label label-success">审核通过</span><br/><br/><span style="margin-top:5px;" class="label label-success">红包发送成功</span>',
        22 => '<span class="label label-success">审核通过</span><br/><br/><span style="margin-top:5px;" class="label label-danger">红包发送失败</span>',
        23=> '<span class="label label-success">审核通过</span><br/><br/><span style="margin-top:5px;" class="label label-warning">红包发送异常</span>',
        24=> '<span class="label label-success">审核通过</span><br/><br/><span style="margin-top:5px;" class="label label-warning">当日红包已发完</span>',
    ];
    $moneyLabel = [
        -1=>'<span class="badge bg-gary">0</span>',
        0=>'--',
        100=>'<span class="badge bg-teal">1.00</span>',
        200=>'<span class="badge bg-aqua">2.00</span>',
        500=>'<span class="badge bg-yellow">5.00</span>',
        588=>'<span class="badge bg-yellow">5.88</span>',
        888=>'<span class="badge bg-yellow">8.88</span>',
    ];
@endphp

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-lg-1">
                    <h3 class="box-title"></h3>
                </div>
                <div class="col-md-12 col-lg-10">
                    <form class="form-inline" id="searchOrDownload">
                        <div class="form-group">
                            <label class="sr-only" for=""></label>
                            <input type="text"
                                   name="user_id"
                                   size="32"
                                   value="{{request()->filled('user_id')?request()->user_id:''}}"
                                   class="form-control" placeholder="请输入用户ID,小票ID或昵称模糊查询">
                        </div>
                        <div class="form-group">
                            <label class="" for="create"></label>
                            <input type="text"
                                   name="daterangepicker_created_at"
                                   size="38"
                                   value="{{request()->filled('daterangepicker_created_at')?request()->daterangepicker_created_at:''}}"
                                   class="form-control" size="" placeholder="请选择上传小票时间范围">
                        </div>
                        <div class="form-group">
                            <label></label>
                            <select class="form-control" name="check_status">
                                <option value="">请选择审核条件</option>
                                <option value="0" {{request()->filled('check_status')&&request()->check_status==0?'selected':''}}>
                                    未审核
                                </option>
                                <option value="11" {{request()->filled('check_status')&&request()->check_status=='11'?'selected':''}}>
                                    通过(已审核)
                                </option>
                                <option value="12" {{request()->filled('check_status')&&request()->check_status=='12'?'selected':''}}>
                                    不通过(已审核)
                                </option>
                                <option value="21" {{request()->filled('check_status')&&request()->check_status=='21'?'selected':''}}>
                                    红包发送成功
                                </option>
                                <option value="22" {{request()->filled('check_status')&&request()->check_status=='22'?'selected':''}}>
                                    红包发送失败
                                </option>
                                <option value="24" {{request()->filled('check_status')&&request()->check_status=='24'?'selected':''}}>
                                    当日红包已发完
                                </option>
                            </select>
                        </div>
                        {{--<div class="form-group">--}}
                        {{--<label></label>--}}
                        {{--<select class="form-control" name="money">--}}
                        {{--<option value="">请选择中奖金额</option>--}}
                        {{--<option value="0" {{request()->filled('money')&&request()->money==0?'selected':''}}>--}}
                        {{--未中奖--}}
                        {{--</option>--}}
                        {{--<option value="123" {{request()->filled('money')&&request()->money==123?'selected':''}}>--}}
                        {{--1.23 元红包--}}
                        {{--</option>--}}
                        {{--<option value="168" {{request()->filled('money')&&request()->money==168?'selected':''}}>--}}
                        {{--1.68 元红包--}}
                        {{--</option>--}}
                        {{--<option value="188" {{request()->filled('money')&&request()->money==188?'selected':''}}>--}}
                        {{--1.88 元红包--}}
                        {{--</option>--}}
                        {{--<option value="288" {{request()->filled('money')&&request()->money==288?'selected':''}}>--}}
                        {{--2.88 元红包--}}
                        {{--</option>--}}
                        {{--<option value="488" {{request()->filled('money')&&request()->money==488?'selected':''}}>--}}
                        {{--4.88 元红包--}}
                        {{--</option>--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-default form-control">查 询</button>
                        </div>
                        <div class="form-group">
                            <button type="button" title="可选择上传小票时间来导出" class="btn btn-default form-control"
                                    id="download">下 载
                            </button>
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
                            <table class="table table-bordered table-hover dataTable" id="ticket-tb">
                                <thead>
                                <tr>
                                    <th width="35" style="text-align: center;">ID</th>
                                    <th style="text-align: center;width:120px;">头像 / 昵称</th>
                                    <th width="60" style="text-align: center;">用户状态</th>
                                    <th style="text-align: center;">图片</th>
                                    <th class="location">地理位置</th>
                                    <th style="text-align: center;">当前进度</th>
                                    <th style="text-align: center;">审核详情</th>
                                    <th style="text-align: center;">中奖<br/>金额(元)</th>
                                    <th style="text-align: center;width: 290px;">红包详情</th>
                                    <th width="60" style="text-align: center;">上传时间</th>
                                    <th width="60" style="text-align: center;">审核时间</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody id="ticket-tb">
                                @foreach($paginator as $ticket)
                                    <tr align="center" id="{{$ticket->id}}">
                                        <?php
                                        $index = $paginator->perPage() * ($paginator->currentPage() - 1);
                                        ?>
                                        <td align="center" title="小票id：{{$ticket->id}}">{{$ticket->id}}</td>
                                        <td align="center">
                                            <img class="tdr-avatar" title="用户id：{{$ticket->user_id}}"
                                                 data-user-id="{{$ticket['user']['id']}}" width="85"
                                                 src="{{$ticket['user']['avatar']?:'https://wx.sanshanwenhua.com/statics/images/common/timg.jpg'}}" alt="">
                                            <div class="nickname">{{$ticket['user']['nickname']}}</div>
                                        </td>
                                        <td align="center"
                                            class="status">{!! $userStatus[$ticket['user']['status']] !!}
                                        </td>
                                        <td align="center">
                                            <img class="tb-img" title="小票ID：{{$ticket['id']}}"
                                                 src="{{$imgPrefix.$ticket['img_url']}}"
                                                 alt="">
                                        </td>
                                        <td class="location" align="center">
                                            {{$ticket['address_str']}}
                                        </td>
                                        <td class="check_status" align="center" title="">
                                            {!! $checkStatusLabel[$ticket['check_status']] !!}
                                        </td>
                                        <td class="check_details" align="center">
                                            <?php
                                            $checkDetails = empty($ticket['result_check_desc']) ? [] : explode('&&', $ticket->result_check_desc);
                                            ?>
                                            @if(!empty($ticket['result_check_msg']))
                                                <div class="check_title">
                                                    {{$ticket['result_check_msg']}}
                                                </div>
                                                @if(count($checkDetails)>0)
                                                    <div class="check_error">
                                                        @foreach($checkDetails as $cd)
                                                            <div class="small">{{$cd}}</div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="money" align="center">
                                            {!! $moneyLabel[$ticket['money']]??$moneyLabel[0] !!}
                                        </td>
                                        <td class="redpack_details" align="center">
                                            <?php
                                            $redpackDetails = json_decode($ticket->result_redpack, true);
                                            ?>
                                            @if(!empty($ticket['result_redpack_msg']))
                                                <div class="check_title">
                                                    {{$ticket['result_redpack_msg']}}
                                                </div>
                                                @if(isset($redpackDetails['mch_billno']))
                                                    <div class="check_error">
                                                        <div style="text-align: left;">说明:
                                                            <span class="small">
                                                                {!! str_replace('&&','<br />',$ticket['result_redpack_desc']) !!}
                                                            </span>
                                                        </div>
                                                        <div style="text-align: left;">商户订单号：
                                                            <span class="small">
                                                                {{$redpackDetails['mch_billno']}}
                                                            </span>
                                                        </div>
                                                        @if(isset($redpackDetails['send_listid']))
                                                            <div style="text-align: left;">微信单号：
                                                                <span class="small">
                                                                {{$redpackDetails['send_listid']}}
                                                            </span>
                                                            </div>
                                                        @endif
                                                        <div style="text-align: left;">发放时间：
                                                            <span class="small">
                                                                {{$ticket->prize_at}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td align="center">{{$ticket['created_at']}}</td>
                                        <td class="checked_at" align="center">{{$ticket['checked_at']}}</td>
                                        <td align="center">
                                            @if(($ticket->check_status==11) || ($ticket->check_status==0))
                                                <button type="button" class="btn btn-sm  btn-danger dis-check"
                                                        data-ticket-id="{{$ticket->id}}">审核不通过
                                                </button>
                                            @elseif($ticket->check_status==12)
                                                <button type="button" class="btn btn-sm  btn-danger pass-check"
                                                        data-ticket-id="{{$ticket->id}}">审核通过
                                                </button>
                                            @elseif($ticket->check_status==22)
                                                <button type="button" class="btn btn-sm  btn-warning reset-redpack"
                                                        data-ticket-id="{{$ticket->id}}">重置红包
                                                </button>
                                            @endif
                                        </td>
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

@section('js')
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        // 导出数据
        $('#download').click(function (e) {
            var str = $('#searchOrDownload').serialize();
            console.log(str);
            // return false;
            location.href = "{{route('l191127.ticket.export')}}" + '?' + str;
        });

        //图片点击放大
        $('.tb-img').on('click', function (e) {
            var image = new Image();
            image.src = $(this).attr('src');
            var viewer = new Viewer(image, {
                hidden: function () {
                    viewer.destroy();
                },
            });
            viewer.show();
        });
        //人工审核不通过
        $('#ticket-tb').on('click', '.dis-check', function (e) {
            if (!confirm('您确认审核不通过吗？')) {
                return false;
            }
            var target = $(e.target);
            $ticketId = $(this).attr('data-ticket-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post("{{route('l191127.ticket.nopass')}}",
                {ticket_id: $ticketId},
                function (res) {
                    if (res.code > 0) {
                        target.parents('tr:eq(0)').find('.check_status:eq(0)').html('<span class="label label-danger">审核不通过</span>');
                        target.parents('tr:eq(0)').find('.check_details:eq(0)').html('<div class="check_title">' + res.data.ticket.result_check_msg +
                            '</div><div class="check_error"><div class="small">' + res.data.ticket.result_check_desc + '</div></div>');
                        target.parent().html('');
                    }
                    layer.close(loading); //关闭loading
                    layer.msg(res.message, {time: 2000});
                });
        });

        //人工审核通过
        $('#ticket-tb').on('click', '.pass-check', function (e) {
            if (!confirm('您确认审核通过吗？')) {
                return false;
            }
            var target = $(e.target);
            $ticketId = $(this).attr('data-ticket-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post("{{route('l191127.ticket.pass')}}",
                {ticket_id: $ticketId},
                function (res) {
                    if (res.code > 0) {
                        target.parents('tr:eq(0)').find('.check_status:eq(0)').html('<span class="label label-success">审核通过</span>');
                        target.parents('tr:eq(0)').find('.check_details:eq(0)').html('<div class="check_title">' + res.data.ticket.result_check_msg +
                            '</div>');
                        target.parent().html('');
                    }
                    layer.close(loading); //关闭loading
                    layer.msg(res.message, {time: 2000});
                });
        });
        //重置红包状态
        $('#ticket-tb').on('click', '.reset-redpack', function (e) {
            if (!confirm('您确认重置吗？')) {
                return false;
            }
            var target = $(e.target);
            $ticketId = $(this).attr('data-ticket-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post("{{route('l191127.ticket.resetredpk')}}",
                {ticket_id: $ticketId},
                function (res) {
                    if (res.code > 0) {
                        target.parents('tr:eq(0)').find('.check_status:eq(0)').html('<span class="label label-success">审核通过</span>');
                        target.parents('tr:eq(0)').find('.redpack_details:eq(0)').html('');
                        target.parent().html('<button type="button" class="btn btn-sm  btn-danger dis-check"' +
                            'data-ticket-id="' + $ticketId + '">审核不通过</button>');
                    }
                    layer.close(loading); //关闭loading
                    layer.msg(res.message, {time: 2000});
                });
        });

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
        $('.tdr-avatar').on('click', function () {
            location.href = "{{url('vlvl/l191127/user/index?user_id=')}}" + $(this).attr('data-user-id');
        });
    </script>
@endsection
