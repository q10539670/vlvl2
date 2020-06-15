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
            红包列表
            {{--            <small>进入黑名单的用户上传的小票将不会显示在以下表格</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="">红包管理</li>
            <li class="active">红包列表</li>
        </ol>
    </section>
@endsection
{{--$imgPrefix = env('APP_URL').'/storage2/';--}}
@php
    $checkStatusLabel = [
        0 => '<span class="label label-default">未分配</span>',
        10 => '<span class="label label-warning">已分配</span>',
        20 => '<span class="label label-success">已分配</span><br/><br/><span style="margin-top:5px;" class="label label-warning">红包发送中...</span>',
        21 => '<span class="label label-success">已分配</span><br/><br/><span style="margin-top:5px;" class="label label-success">红包发送成功</span>',
        22 => '<span class="label label-success">已分配</span><br/><br/><span style="margin-top:5px;" class="label label-danger">红包发送失败</span>',
        23=> '<span class="label label-success">已分配</span><br/><br/><span style="margin-top:5px;" class="label label-warning">红包发送异常</span>',
    ];
    $moneyLabel = [
        -1=>'<span class="badge bg-gary">0</span>',
        0=>'--',
    ];
    foreach ($moneys as $money) {
        $moneyLabel[$money] = '<span class="badge bg-yellow">'. $money/100 . '</span>';
    }
    $redMoneyLabel = [
        -1=>'<span class="badge bg-teal">0</span>',
        0=>'--',
    ];
    foreach ($moneys as $money) {
        $redMoneyLabel[$money] = '<span class="badge bg-aqua">'. $money/100 . '</span>';
    }
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
                                   class="form-control" placeholder="请输入用户ID或昵称模糊查询">
                        </div>
                        <div class="form-group">
                            <label></label>
                            <select class="form-control" name="status">
                                <option value="">请选择条件</option>
                                <option value="0" {{request()->filled('status')&&request()->status==0?'selected':''}}>
                                    未分配
                                </option>
                                <option
                                    value="11" {{request()->filled('status')&&request()->status=='11'?'selected':''}}>
                                    已分配
                                </option>
                                <option
                                    value="21" {{request()->filled('status')&&request()->status=='21'?'selected':''}}>
                                    红包发送成功
                                </option>
                                <option
                                    value="22" {{request()->filled('status')&&request()->status=='22'?'selected':''}}>
                                    红包发送失败
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label></label>
                            <select class="form-control" name="money">
                                <option value="">请选择中奖金额</option>
                                @foreach($moneys as $money)
                                    <option
                                        value="{{$money}}" {{request()->filled('money')&&request()->money==$money?'selected':''}}>
                                        {{$money/100}} 元红包
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default form-control">查 询</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default form-control"
                                    id="download">下 载
                            </button>
                        </div>
                        <div class="form-group">
                            <span class="label label-success">分配金额:{{$act1MoneyCount['before']/100}}元</span>
                        </div>
                        <div class="form-group">
                            <span class="label label-warning">发放金额:{{$act1MoneyCount['after']/100}}元</span>
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
                                    <th style="text-align: center;">ID</th>
                                    <th style="text-align: center;width:120px;">头像 / 昵称</th>
                                    <th style="text-align: center;">当前进度</th>
                                    <th style="text-align: center;">分配金额(元)</th>
                                    <th style="text-align: center;">发放金额(元)</th>
                                    <th style="text-align: center;width: 290px;">红包详情</th>
                                    {{--                                    <th width="60" style="text-align: center;">发送时间</th>--}}
                                    {{--                                    <th width="60" style="text-align: center;">审核时间</th>--}}
                                </tr>
                                </thead>
                                <tbody id="ticket-tb">
                                @foreach($paginator as $ticket)
                                    <tr align="center" id="{{$ticket->id}}">
                                        <?php
                                        $index = $paginator->perPage() * ($paginator->currentPage() - 1);
                                        ?>
                                        <td align="center" title="id：{{$ticket->id}}">{{$ticket->id}}</td>
                                        <td align="center">
                                            <img class="tdr-avatar" title="用户id：{{$ticket->user_id}}"
                                                 data-user-id="{{$ticket['user']['id']}}" width="85"
                                                 src="{{$ticket['user']['avatar']?:'https://wx.sanshanwenhua.com/statics/images/common/timg.jpg'}}"
                                                 alt="">
                                            <div class="nickname">{{$ticket['user']['nickname']}}</div>
                                        </td>
                                        <td class="status" align="center" title="">
                                            {!! $checkStatusLabel[$ticket['status']] !!}
                                        </td>
                                        <td class="money" align="center">
                                            {!! $moneyLabel[$ticket['money']]??$moneyLabel[0] !!}
                                        </td>
                                        <td class="money" align="center">
                                            {!! $moneyLabel[$ticket['red_money']]??$redMoneyLabel[0] !!}
                                        </td>
                                        <td class="redpack_details" align="center">
                                            <?php
                                            $redpackDetails = json_decode($ticket['redpack_discribe'], true);
                                            //                                            var_dump($redpackDetails);
                                            ?>
                                            @if(!empty($ticket['redpack_return_msg']))
                                                <div class="check_title">
                                                    {{$ticket['redpack_return_msg']}}
                                                </div>
                                                @if(isset($redpackDetails['mch_billno']))
                                                    <div class="check_error">
                                                        <div style="text-align: left;">说明:
                                                            <span class="small">
                                                                {!! str_replace('&&','<br />',$redpackDetails['err_code_des']) !!}
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
                                                                {{$ticket->prized_at}}
                                                            </span>
                                                        </div>
                                                    </div>
                                    @endif
                                    @endif
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
            location.href = "{{route('l191127.act1.export')}}" + '?' + str;
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
