@extends('layouts.adminlte.simple-a')
<?php
$statusNow = request()->input('status', '');
$ststusArr = [
    1 => '已中奖',
    2 => '未中奖'
];

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
        .table-responsive{
            overflow: hidden;
            overflow-y: scroll;
        }
    </style>
@endsection
@section('content')
    <!-- 模态框（Modal） -->
    <div class="modal fade" id="mainModal"  role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">中奖名单</h4>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable" id="ss-tb">
                                <thead>
                                <tr>
                                    <th style="text-align: center;">姓名</th>
                                    <th style="text-align: center;">手机号码</th>
                                    <th style="text-align: center;">身份证号码</th>
                                    <th style="text-align: center;">奖品</th>
                                </tr>
                                </thead>
                                <tbody class="users">

                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <a type="submit" class="btn btn-info prize">再抽一次</a>
                                <a type="submit" class="btn btn-primary" onclick="send()">确认抽奖</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-4 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x191219/index')}}">
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
                                <option {{($statusNow =='2')?'selected':''}}  value="2">未中奖</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已中奖</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">查询</button>
                        </div>
                        <div class="form-group">
                            <a href="{!! $exportUrl !!}" class="btn btn-default form-control">下载</a>
                        </div>
                        <div class="form-group">
                            <a data-toggle="modal" data-target="#mainModal"
                               class="btn btn-info form-control prize">抽奖</a>
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
                                    <th style="text-align: center;">身份证号码</th>
                                    <th style="text-align: center;">奖品</th>
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
                                        <td align="center">{{$user['id_num']}}</td>
                                        <td align="center">{{$user['prize']}}</td>
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
@endsection
@section('js')
    <script>
        $('.prize').click(function () {
            layer.confirm('确认抽奖吗？', function () {
                $.ajax({
                    url: '{{url('vlvl/x191219/prize')}}',
                    data: {_token: '{{csrf_token()}}'},
                    type: 'post',
                    success: function (data) {
                        if (data.data && data.code != -1) {
                            console.log(data);
                            $(".users").empty();
                            var html = '';
                            $.each (data.data.first_prize, function (index, value) {
                                html += "<tr align=\"center\"><td>" + value.name + "</td><td>" + value.phone + "</td><td>"
                                    + value.id_num + "</td><td>一等奖</td></tr>";
                            });
                            $.each(data.data.second_prize, function (index, value) {
                                html += "<tr align=\"center\"><td>" + value.name + "</td><td>" + value.phone + "</td><td>"
                                    + value.id_num + "</td><td>二等奖</td></tr>";
                            });
                            $.each(data.data.third_prize, function (index, value) {
                                html += "<tr align=\"center\"><td>" + value.name + "</td><td>" + value.phone + "</td><td>"
                                    + value.id_num + "</td><td>三等奖</td></tr>";
                            });
                            $.each(data.data.fourth_prize, function (index, value) {
                                html += "<tr align=\"center\"><td>" + value.name + "</td><td>" + value.phone + "</td><td>"
                                    + value.id_num + "</td><td>四等奖</td></tr>";
                            });
                            $.each(data.data.fifth_prize, function (index, value) {
                                html += "<tr align=\"center\"><td>" + value.name + "</td><td>" + value.phone + "</td><td>"
                                    + value.id_num + "</td><td>五等奖</td></tr>";
                            });
                            if (data.data.first_prize_ids) {
                                html += "<input type='hidden' name='first_prize_ids' id='first_prize_ids' value='" + data.data.first_prize_ids + "'>"
                            }
                            if (data.data.second_prize_ids) {
                                html += "<input type='hidden' name='second_prize_ids' id='second_prize_ids' value='" + data.data.second_prize_ids + "'>"
                            }
                            if (data.data.third_prize_ids) {
                                html += "<input type='hidden' name='third_prize_ids' id='third_prize_ids' value='" + data.data.third_prize_ids + "'>"
                            }
                            if (data.data.fourth_prize_ids) {
                                html += "<input type='hidden' name='fourth_prize_ids' id='fourth_prize_ids' value='" + data.data.fourth_prize_ids + "'>"
                            }
                            if (data.data.fifth_prize_ids) {
                                html += "<input type='hidden' name='fifth_prize_ids' id='fifth_prize_ids' value='" + data.data.fifth_prize_ids + "'>"
                            }
                            $(".users").append(html);
                            layer.msg('抽奖成功,请确认抽奖结果!', {icon: 1, time: 1000});
                        } else if (data.code == -1) {
                            layer.msg(data.message, {icon: 5, time: 1000});
                            $('#mainModal').modal('hide');
                        } else {
                            layer.msg('抽奖失败,请再试一次或联系管理员!', {icon: 5, time: 1000});
                            $('#mainModal').modal('hide');
                        }
                    }
                })
            })
        })
        function send() {
            var first_prize_ids= $('#first_prize_ids').val();
            var second_prize_ids = $('#second_prize_ids').val();
            var third_prize_ids = $('#third_prize_ids').val();
            var fourth_prize_ids = $('#fourth_prize_ids').val();
            var fifth_prize_ids = $('#fifth_prize_ids').val();
            console.log(first_prize_ids);
            layer.prompt({title: '请输入口令(确认抽奖结果后不能再更改,请慎重考虑后再确认)', formType: 0}, function (text, index) {
                layer.close(index);
                $.ajax({
                    url: '{{url('vlvl/x191219/send')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        first_prize_ids: first_prize_ids,
                        second_prize_ids: second_prize_ids,
                        third_prize_ids: third_prize_ids,
                        fourth_prize_ids: fourth_prize_ids,
                        fifth_prize_ids: fifth_prize_ids,
                        token: text
                    },
                    type: 'post',
                    success: function (res) {
                        console.log(res);
                        if (res.code == 1) {
                            $('#mainModal').modal('hide');
                            layer.msg('抽奖确认成功!', {icon: 1, time: 1000});
                        } else if (res.code == -1) {
                            layer.msg(res.message, {icon: 5, time: 1000});
                        } else if (res.code == -2) {
                            layer.msg(res.message, {icon: 5, time: 1000});
                        } else if (res.code == -3) {
                            layer.msg(res.message, {icon: 5, time: 1000});
                        }
                    }
                })
            })
        }
    </script>
@endsection
