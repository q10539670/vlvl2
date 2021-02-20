@extends('layouts.adminlte.simple-e')
<?php
$statusNow = request()->input('order', '');
$infoNow = request()->input('info', '');
$statusLabel = [
    1 => '<span class="badge bg-gary">隐藏</span>',
    0 => '<span class="badge bg-teal">显示</span>',
]
?>
@section('title', $title)

@section('css')
    <link rel="stylesheet" href="{{asset('/vlvl/layui/css/modules/layer/default/layer.css')}}">
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
    <!-- 模态框（Modal） -->
    <div class="modal fade" id="mainModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">上传照片</h4>
                </div>
                <form action="" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">信息</label>
                                    <div class="col-md-5">
                                        <input type="text" id="info" name="info" class="form-control" size="32"
                                               placeholder="请输入照片信息" value="{{$infoNow}}">
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label for="image" class="col-md-2 control-label">照片</label>
                                    <div class="col-md-5">
                                        <input type="file" name="image" id="image" class="form-control" size="32">
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="submit" class="btn btn-primary" id="send">确认上传</a>
                    </div>
                </form>
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
                    <form class="form-inline" method="get" action="{{url('vlvl/x210204/index')}}">
                        <div class="form-group">
                            <select name="order" class="form-control">
                                <option value="">请选择排序方式</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">按时间</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">按投票</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">查询</button>
                        </div>
                        <div class="form-group">
                            <a href="{!! $exportImagesUrl !!}" class="btn btn-default form-control">下载投票结果</a>
                        </div>
                        <div class="form-group">
                            <a href="{!! $exportLogsUrl !!}" class="btn btn-default form-control">下载投票记录</a>
                        </div>
                        <div class="form-group">
                            <a data-toggle="modal" data-target="#mainModal"
                               class="btn btn-facebook form-control" id="sendCoupon">上传图片</a>
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
                                    <th style="text-align: center;">照片信息</th>
                                    <th style="text-align: center;">照片</th>
                                    <th style="text-align: center;">状态</th>
                                    <th style="text-align: center;">票数</th>
                                    <th style="text-align: center;">参与时间</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $image)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td align="center">{{$image->info}}</td>
                                        <td align="center" class="image">
                                            @if($image->image)
                                                <img class="tb-img" title="用户ID：{{$image['id']}}"
                                                     src="{{$prefix.$image['image']}}"
                                                     alt="">
                                            @endif
                                        </td>
                                        <td align="center"
                                            class="status_{{$image['id']}}">{!! $statusLabel[$image['status']] !!}</td>
                                        <td align="center">{{$image['poll']}}</td>
                                        <td align="center">{{$image['created_at']}}</td>
                                        <td align="center">
                                            @if($image['status'] == 0)
                                                <a class="btn btn-xs btn-danger" onclick="hide(this,{{$image['id']}})">隐藏</a>
                                            @else
                                                <a class="btn btn-xs btn-instagram"
                                                   onclick="display(this,{{$image['id']}})">显示</a>
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
        function hide(obj, id) {
            layer.confirm('确认隐藏吗？', {
                btn: ['确认', '取消']
            }, function () {
                $.ajax({
                    url: '{{url('vlvl/x210204/hide')}}',
                    data: {id: id, _token: '{{csrf_token()}}'},
                    type: 'post',
                    success: function (res) {
                        if (res == 1) {
                            $(obj).removeClass('btn-danger');
                            $(obj).addClass('btn-instagram');
                            $(obj).removeAttr('onclick');
                            $(obj).attr('onclick', 'display(this,' + id + ')');
                            $(obj).html('显示')
                            $('.status_' + id).empty().append('<span class="badge bg-gary">隐藏</span>');
                            layer.msg('隐藏成功', {icon: 1, time: 1000})
                        } else {
                            layer.msg('隐藏失败', {icon: 5, time: 1000})
                        }
                    }
                })
            },function () {
                layer.msg('取消成功', {icon: 1, time: 1000})
            })
        }

        function display(obj, id) {
            layer.confirm('确认显示吗？', {
                btn: ['确认', '取消']
            }, function () {
                $.ajax({
                    url: '{{url('vlvl/x210204/display')}}',
                    data: {id: id, _token: '{{csrf_token()}}'},
                    type: 'post',
                    success: function (res) {
                        if (res == 1) {
                            $(obj).removeClass('btn-instagram');
                            $(obj).addClass('btn-danger');
                            $(obj).removeAttr('onclick');
                            $(obj).attr('onclick', 'hide(this,' + id + ')');
                            $(obj).html('隐藏')
                            $('.status_' + id).empty().append('<span class="badge bg-teal">显示</span>');
                            layer.msg('显示成功', {icon: 1, time: 1000})
                        } else {
                            layer.msg('显示失败', {icon: 5, time: 1000})
                        }
                    }
                })
            },function () {
                layer.msg('取消成功', {icon: 1, time: 1000})
            })
        }
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
        $('#send').click(function () {
            var formData = new FormData();
            formData.append('image', document.getElementById('image').files[0]);
            formData.append('info', document.getElementById('info').value);
            formData.append('_token', '{{csrf_token()}}');
            layer.confirm('确认上传吗？', function () {
                $.ajax({
                    url: '{{route('x210204.upload')}}',
                    data: formData,
                    type: 'post',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        // var data = JSON.parse(data);
                        console.log(data);
                        if (data.code === 1) {
                            layer.msg(data.message, {icon: 1, time: 5000})
                        } else {
                            layer.msg(data.error, {icon: 5, time: 5000})
                        }
                    }
                });
            })
        });
    </script>
@endsection
