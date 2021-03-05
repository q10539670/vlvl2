@extends('layouts.adminlte.simple-a')
<?php
$statusNow = request()->input('order', '');
$userStatus = [
    0 => '<span class="label label-info">正常</span>',
    1 => '<span class="label label-danger">禁用</span>',
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
                <div class="col-md-4 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x200113/index')}}">

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
                                <option {{($statusNow =='0')?'selected':''}}  value="0">按人气</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">按最新</option>
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
                                    <th style="text-align: center;">状态</th>
                                    <th style="text-align: center;">姓名</th>
                                    <th style="text-align: center;">电话</th>
                                    <th style="text-align: center;">照片</th>
                                    <th style="text-align: center;">票数</th>
                                    <th style="text-align: center;">参与时间</th>
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
                                        <td align="center" class="status">{!! $userStatus[$user['status']] !!}</td>
                                        <td align="center">{{$user['name']}}</td>
                                        <td align="center">{{$user['phone']}}</td>
                                        <td align="center" class="image">
                                            @if($user->image)
                                                <img class="tb-img" title="用户ID：{{$user['id']}}"
                                                     src="{{$imgPrefix.$user['image']}}"
                                                     alt="">
                                            @endif
                                        </td>
                                        <td align="center">{{$user['polls']}}</td>
                                        <td align="center">{{$user['img_at']}}</td>
                                        <td align="center">
                                            @if($user->image)
                                                <button type="button" class="btn btn-sm  btn-danger delete"
                                                        data-id="{{$user->id}}">删除照片
                                                </button>
                                            @endif
                                            @if($user->status == 0)
                                                <button type="button" class="btn btn-sm  btn-warning black-list"
                                                        data-id="{{$user->id}}">拉黑
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

        //删除
        $('.delete').on('click', function (e) {
            if (!confirm('您确认删除吗？')) {
                return false;
            }
            var target = $(e.target);
            $userId = $(this).attr('data-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post("{{route('x200113.delete')}}",
                {id: $userId},
                function (res) {
                    if (res) {
                        target.parents('tr:eq(0)').find('.image:eq(0)').html('');
                        target.parent().html('');
                    }
                    layer.close(loading); //关闭loading
                    layer.msg('删除成功', {time: 2000});
                });
        });

        //拉黑
        $('.black-list').on('click', function (e) {
            if (!confirm('您确认拉黑吗？')) {
                return false;
            }
            var target = $(e.target);
            $userId = $(this).attr('data-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post("{{route('x200113.black')}}",
                {id: $userId},
                function (res) {
                    if (res) {
                        target.parent().parent().find('.status:eq(0)').html('<span class="label label-danger">禁用</span>');
                        target.parent().find('.black-list').remove();
                    }
                    layer.close(loading); //关闭loading
                    layer.msg('拉黑成功', {time: 2000});
                });
        });
    </script>
@endsection
