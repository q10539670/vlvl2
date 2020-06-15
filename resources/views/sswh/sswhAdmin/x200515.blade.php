@extends('layouts.adminlte.simple-a')
<?php
$orderNow = request()->input('order', '');
$weekNow = request()->input('week', '');
$map = [
    0 => '<span class="badge bg-gary">无</span>',
    1 => '<span class="badge bg-teal">故宫</span>',
    2 => '<span class="badge bg-aqua">黄鹤楼</span>',
    3 => '<span class="badge bg-yellow">埃菲尔铁塔</span>',
]
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
                <div class="col-md-2 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x200515/index')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="order" class="form-control">
                                <option value="">请选择排序方式</option>
                                <option {{($orderNow =='1')?'selected':''}} value="1">按排名</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="week" class="form-control">
                                <option value="">当前排名</option>
                                <option {{($weekNow =='1')?'selected':''}} value="1">第一周排名</option>
                                <option {{($weekNow =='2')?'selected':''}} value="2">第二周排名</option>
                                <option {{($weekNow =='3')?'selected':''}} value="3">第三周排名</option>
                                <option {{($weekNow =='4')?'selected':''}} value="4">第四周排名</option>
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
                                    <th style="text-align: center;">电话</th>
                                    <th style="text-align: center;">地址</th>
                                    <th style="text-align: center;">地图</th>
                                    <th style="text-align: center;">用时</th>
                                    <th style="text-align: center;">排名</th>
                                    <th style="text-align: center;">更新时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65"
                                                 src="{{strlen($user['avatar']) > 20?$user['avatar']:'https://wx.sanshanwenhua.com/statics/images/common/timg.jpg'}}"
                                                 alt="">
                                            <div class="nickname">{{$user['nickname']}}</div>
                                        </td>
                                        <td>{{$user['name']}}</td>
                                        <td>{{$user['phone']}}</td>
                                        <td>{{$user['address']}}</td>
                                        <td>
                                            {!! $map[$user['map_1']] !!}<br>
                                            {!! $map[$user['map_2']] !!}<br>
                                            {!! $map[$user['map_3']] !!}<br>
                                            {!! $map[$user['map_4']] !!}
                                        </td>
                                        <td>
                                            @if($user->time_1 != '')
                                                <a class="btn"
                                                   onclick="detail({{$user->time_1}})">{{$user['score_1']/100}}秒</a><br>
                                            @else
                                                {{$user['score_1']/100}}秒<br>
                                            @endif
                                            @if($user->time_2 != '')
                                                <a class="btn"
                                                   onclick="detail({{$user->time_2}})">{{$user['score_2']/100}}秒</a><br>
                                            @else
                                                {{$user['score_2']/100}}秒<br>
                                            @endif
                                            @if($user->time_3 != '')
                                                <a class="btn"
                                                   onclick="detail({{$user->time_3}})">{{$user['score_3']/100}}秒</a><br>
                                            @else
                                                {{$user['score_3']/100}}秒<br>
                                            @endif
                                            @if($user->time_4 != '')
                                                <a class="btn"
                                                   onclick="detail({{$user->time_4}})">{{$user['score_4']/100}}秒</a><br>
                                            @else
                                                {{$user['score_4']/100}}秒
                                            @endif
                                        </td>
                                        <td>
                                            {{$user['ranking_1']}}<br>
                                            {{$user['ranking_2']}}<br>
                                            {{$user['ranking_3']}}<br>
                                            {{$user['ranking_4']}}
                                        </td>
                                        <td>{{$user['updated_at']}}</td>
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
        function formatterTime(time, fmt) {

            if (!time) {

                return '';

            }

            if (typeof (time) == "number") {

                var TIME = new Date(time);

                var z = {

                    M: TIME.getMonth() + 1,

                    d: TIME.getDate(),

                    h: TIME.getHours(),

                    m: TIME.getMinutes(),

                    s: TIME.getSeconds()

                };

                fmt = fmt.replace(/(M+|d+|h+|m+|s+)/g, function (v) {

                    return ((v.length > 1 ? "0" : "") + eval('z.' + v.slice(-1))).slice(-2);

                });

                return fmt.replace(/(y+)/g, function (v) {

                    return TIME.getFullYear().toString().slice(-v.length);

                });

            } else return time;

        }

        function detail(input) {
            var msg = '';
            var file = '';
            console.log(typeof (input.info))
            $.each(input, function (key, value) {    //objTmp对象数据
                if (key == 'start') {
                    file = (value).toString();
                    msg += '开始时间:' + formatterTime(value, "yyyy-MM-dd hh:mm:ss") + '.' + file.substr(file.length - 3) + '<br>'
                }
                if (key == 'info') {
                    msg += '摆放时间:' + '<br>'
                    $.each(value, function (k, v) {
                        file = (v).toString();
                        msg += '第' + (Number(k)+1) + '个' + ':' + formatterTime(v, "yyyy-MM-dd hh:mm:ss") + '.' + file.substr(file.length - 3) + '<br>'
                    })
                }
                if (key == 'end') {
                    file = (value).toString();
                    msg += '结束时间:' + formatterTime(value, "yyyy-MM-dd hh:mm:ss") + '.' + file.substr(file.length - 3) + '<br>'
                }
                // if ($.isPlainObject(value)) {
                //     file = key + ':';
                //     $.each(value, function (k, v) {
                //         file += '<br>&nbsp;&nbsp;&nbsp;&nbsp;' + '<span style="color:#999580">' + k + ": " + formatterTime(v ,"yyyy-MM-dd hh:mm:ss");
                //     })
                // } else {
                //     msg += key + ": " + formatterTime(value ,"yyyy-MM-dd hh:mm:ss") + '<br>';
                // }
            });
            msg = msg + '</span>';
            layer.open({
                title: '游戏时间'
                , content: msg
            });
        }
    </script>
@endsection
