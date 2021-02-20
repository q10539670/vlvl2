@extends('layouts.adminlte.simple-e')
<?php
$statusNow = request()->input('status', '');
$prizeNow = request()->input('prize_id', '');
$YHNow = request()->input('yh', '');
$TYNow = request()->input('ty', '');
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
                    <form class="form-inline" method="get" action="{{url('vlvl/x210205/index')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrMobile"
                                   value="{{(request()->has('nameOrMobile')&&(request()->nameOrMobile!=''))?request()->nameOrMobile:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择是否抽奖</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未抽奖</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已抽奖</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="prize_id" class="form-control">
                                <option value="">请选择中奖将奖品</option>
                                <option {{($prizeNow =='0')?'selected':''}}  value="0">未中奖</option>
                                <option {{($prizeNow =='1')?'selected':''}} value="1">果岭推杆体验券(50个球)</option>
                                <option {{($prizeNow =='2')?'selected':''}} value="2">恒温游泳池体验券</option>
                                <option {{($prizeNow =='3')?'selected':''}} value="3">游艇观湖体验券</option>
                                <option {{($prizeNow =='4')?'selected':''}} value="4">1.08元现金红包</option>
                                <option {{($prizeNow =='5')?'selected':''}} value="5">1.18元现金红包</option>
                                <option {{($prizeNow =='6')?'selected':''}} value="6">2.08元现金红包</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="yh" class="form-control">
                                <option value="">请选择是否核销优惠券</option>
                                <option {{($YHNow =='0')?'selected':''}}  value="0">未核销</option>
                                <option {{($YHNow =='1')?'selected':''}} value="1">已核销</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="ty" class="form-control">
                                <option value="">请选择是否核销体验券</option>
                                <option {{($TYNow =='0')?'selected':''}}  value="0">未核销</option>
                                <option {{($TYNow =='1')?'selected':''}} value="1">已核销</option>
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
                                    <th style="text-align: center;">昵称</th>
                                    <th style="text-align: center;">姓名</th>
                                    <th style="text-align: center;">电话</th>
                                    <th style="text-align: center;">奖品</th>
                                    <th style="text-align: center;">中奖时间</th>
                                    <th style="text-align: center;">优惠券核销时间</th>
                                    <th style="text-align: center;">体验券核销时间</th>
                                    <th style="text-align: center;">操作</th>
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
                                        <td align="center">{{$user['mobile']}}</td>
                                        <td align="center">{{$user['prize']}}</td>
                                        <td align="center">{{$user['prized_at']}}</td>
                                        <td align="center"
                                            class="time1_{{$user['id']}}">{{$user['verification1_at']}}</td>
                                        <td align="center"
                                            class="time2_{{$user['id']}}">{{$user['verification2_at']}}</td>
                                        <td align="center">
                                            <div>
                                                @if($user['verification1_at'] != '')
                                                    <a class="btn btn-xs btn-danger" disabled="">优惠券已核销</a>
                                                @else
                                                    <a class="btn btn-xs btn-instagram"
                                                       onclick="verifyYH(this,{{$user['id']}})">核销优惠券</a>
                                                @endif
                                            </div>
                                            @if(in_array($user['prize_id'],[1,2,3]))
                                                <p></p>
                                            <div>

                                                    @if($user['verification2_at'] != '')
                                                        <a class="btn btn-xs btn-danger" disabled="">体验券已核销</a>
                                                    @else
                                                        <a class="btn btn-xs btn-info"
                                                           onclick="verifyTY(this,{{$user['id']}})">核销体验券</a>
                                                    @endif

                                            </div>
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
        function verifyYH(obj, id) {
            layer.confirm('确认核销吗？', {
                    btn: ['核销优惠券', '取消']
                }, function () {
                    $.ajax({
                        url: '{{url('vlvl/x210205/verify')}}',
                        data: {id: id, type: 1, _token: '{{csrf_token()}}'},
                        type: 'post',
                        success: function (res) {
                            if (res['code'] === 1) {
                                $(obj).removeClass('btn-info');
                                $(obj).addClass('btn-danger');
                                $(obj).removeAttr('onclick');
                                $(obj).attr('disabled', 'disabled');
                                $(obj).html('优惠券已核销');
                                $('.time1_' + id).html(res['time'])
                                layer.msg('优惠券核销成功!', {icon: 1, time: 1000});
                            } else {
                                layer.msg('优惠券核销失败!', {icon: 5, time: 1000});
                            }
                        }
                    })
                }, function () {
                    layer.msg('已取消', {icon: 1, time: 1000});
                }
            )
        }

        function verifyTY(obj, id) {
            layer.confirm('确认核销吗？', {
                    btn: ['核销体验券', '取消']
                }, function () {
                    $.ajax({
                        url: '{{url('vlvl/x210205/verify')}}',
                        data: {id: id, type: 2, _token: '{{csrf_token()}}'},
                        type: 'post',
                        success: function (res) {
                            if (res['code'] === 1) {
                                $(obj).removeClass('btn-info');
                                $(obj).addClass('btn-danger');
                                $(obj).removeAttr('onclick');
                                $(obj).attr('disabled', 'disabled');
                                $(obj).html('体验券已核销');
                                $('.time2_' + id).html(res['time'])
                                layer.msg('体验券核销成功!', {icon: 1, time: 1000});
                            } else {
                                layer.msg('体验券核销失败!', {icon: 5, time: 1000});
                            }
                        }
                    })
                }, function () {
                    layer.msg('已取消', {icon: 1, time: 1000});
                }
            )
        }
    </script>
@endsection
