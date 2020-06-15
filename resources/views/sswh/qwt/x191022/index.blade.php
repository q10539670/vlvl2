@extends('layouts.adminlte.simple-b-qwt')

@section('title', $title)

@section('nav')
    <li class="active disabled">
        <!-- Menu toggle button -->
        <a href="#" title="">
            用户列表
        </a>
    </li>
    <li class="">
        <!-- Menu toggle button -->
{{--        <a href="{{url('sspc/l190617a/area')}}" title="">--}}
        <a href="{{$areaUrl}}" title="">
            地区统计
        </a>
    </li>
@endsection

@section('css')
    <link href="https://cdn.bootcss.com/bootstrap-daterangepicker/2.1.25/daterangepicker.css" rel="stylesheet">
    <style>
        .qwt-avatar {
            border: 1px solid silver;
            border-radius: 3px;
            padding: 5px;
            width: 75px;
            height: 75px;
        }

        #qwt-tb th {
            vertical-align: middle;
        }

        #qwt-tb td {
            vertical-align: middle;
        }
    </style>
@endsection


@section('content')
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-4">
                    <h3 class="box-title">用户列表</h3>
                </div>
                <div class="col-sm-8">
                    <form class="form-inline pull-right">
                        <div class="form-group" method="get">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nickname"
                                   value="{{(request()->has('nickname')&&(request()->nickname!=''))?request()->nickname:''}}"
                                   class="form-control" size="25" placeholder="请输入昵称查询">
                        </div>
                        <div class="form-group">
                            <label></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" size="38" class="form-control pull-right" id="reservation" />
                                <input type="hidden" id = "startTime" name="startTime" class="form-control" />
                                <input type="hidden" id = "endTime" name="endTime" class="form-control" />
                            </div>
                            <!-- /.input group -->
                        </div>
                        <button type="submit" class="btn btn-default">查询</button>
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
                        <table class="table table-bordered table-hover dataTable" id="qwt-tb">
                            <thead>
                            <tr>
                                <th width="35" align="center">序号</th>
                                <th style="text-align: center;">头像</th>
                                <th style="text-align: center;">昵称</th>
                                <th style="text-align: center;">用户所选<br/>归属地</th>
                                <th style="text-align: center;">定位地址</th>
                                <th style="text-align: center;">抽奖详情</th>
                                <th style="text-align: center;">参与时间</th>
                                <th style="text-align: center;">抽奖时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paginator as $user)
                                <tr align="center">
                                    <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                    <td align="center">{{$index+$loop->iteration}}</td>
                                    <td align="center">
                                        <img src="{{$user['avatar']}}" class="qwt-avatar"/>
                                    </td>
                                    <td align="center">{{$user['nickname']}}</td>
                                    <td align="center">
                                        {{--{{$user['userCity']['name']}}--}}
                                        {!! empty($user['virtual_address_str'])?'--':str_replace('/','<br />',$user['virtual_address_str']) !!}
                                    </td>
                                    {{--<td align="center">{{$user['phoneCity']['name']}}</td>--}}
                                    <td align="center">
                                        {{$user['address_str']}}
                                    </td>
                                    <td align="center">
                                        @if($user['status'] ==0)
                                            未抽奖
                                        @elseif($user['status'] ==1)
                                            {{$user['money']}}元红包
                                        @elseif($user['status'] ==2)
                                            未中奖（红包发送失败）
                                        @elseif($user['status'] ==3)
                                            未中奖
                                        @endif
                                    </td>
                                    <td align="center">{{$user['created_at']}}</td>
                                    <td align="center">
                                        {{isset($user['prize_at'])?$user['prize_at']:'--:--:--'}}
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
    <script src="https://cdn.bootcss.com/bootstrap-daterangepicker/2.1.25/moment.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-daterangepicker/2.1.25/daterangepicker.js"></script>
    <script>
        var dateRangePicker ={
            uriObj:null,
            config:function(){
                var uriObj = dateRangePicker.parseUrl();
                var startTime = uriObj.params.startTime||moment().format('YYYY-MM-DD 00:00:00');
                var endTime = uriObj.params.endTime||moment().format('YYYY-MM-DD 23:00:00');
                return{
                    // singleDatePicker: true,
                    // autoUpdateInput: false,
                    timePicker: true,
                    "timePicker24Hour": true,
                    timePickerIncrement: 5,
                    timePickerSeconds: true,
                    'locale': {
                        // "format": 'YYYY-MM-DD',
                        "format": "YYYY-MM-DD HH:mm:ss",
                        "separator": " 至 ",
                        "applyLabel": "确定",
                        "cancelLabel": "清空",
                        "fromLabel": "起始时间",
                        "toLabel": "结束时间'",
                        "customRangeLabel": "自定义",
                        "weekLabel": "W",
                        "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
                        "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                        "firstDay": 1
                    },
                    ranges: {
                        '今天': [moment().format('YYYY-MM-DD 00:00:00'), moment().format('YYYY-MM-DD 23:59:59')],
                        '昨天': [moment().subtract(1, 'days').format('YYYY-MM-DD 00:00:00'), moment().subtract(1, 'days').format('YYYY-MM-DD 23:59:59')],
                        // '最近7日': [moment().subtract(6, 'days'), moment()],
                        // '最近30日': [moment().subtract(29, 'days'), moment()],
                        // '本月': [moment().startOf('month'), moment().endOf('month')],
                        // '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    format: 'YYYY-MM-DD',
                    startDate: startTime,
                    // startDate: '2018-11-15 00:00:01',
                    endDate: endTime
                }
            },
            calllback:function(start, end){
                timeRangeChange = [start.format('YYYY-MM-DD HH:mm:ss'), end.format('YYYY-MM-DD HH:mm:ss')];
                console.log(timeRangeChange);
            },
            parseUrl:function(){
                if(dateRangePicker.uriObj){
                    return dateRangePicker.uriObj;
                }
                var url = window.location.href.indexOf('#') > -1 ? window.location.href.split('#')[0].replace(/^(http|https):\/\//i, '') :
                    window.location.href.replace(/^(http|https):\/\//i, '');
                var urlStr;
                var params = {};
                if (url.indexOf('?') > -1) {
                    urlStr = url.split('?')[0];
                    var paramsArr = url.split('?')[1].split('&');
                    for (var i = 0; i < paramsArr.length; i++) {
                        params[paramsArr[i].split('=')[0]] = decodeURIComponent(paramsArr[i].split('=')[1]).replace('+',' ');
                    }
                } else {
                    urlStr = url;
                }
                dateRangePicker.uriObj = {
                    domain: urlStr.split('/')[0], // 域名
                    params: params, //参数
                };
                return dateRangePicker.uriObj;
            }
        };
        // console.log(dateRangePicker.parseUrl());
        $('#reservation').daterangepicker(dateRangePicker.config(),dateRangePicker.calllback)
        .on('cancel.daterangepicker', function(ev, picker) {
            $("#reservation").val("请选择日期");
            $("#startTime").val("");
            $("#endTime").val("");
        }).on('apply.daterangepicker', function(ev, picker) {
            $("#startTime").val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            $("#endTime").val(picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
            $("#reservation").val(picker.startDate.format('YYYY-MM-DD HH:mm:ss')+" 至 "+picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
        });
        if(!dateRangePicker.parseUrl().params.startTime||dateRangePicker.parseUrl().params.startTime==''){
            $("#reservation").val("请选择日期");
        }
    </script>
@endsection