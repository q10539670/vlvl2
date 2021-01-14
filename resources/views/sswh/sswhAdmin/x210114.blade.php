@extends('layouts.adminlte.simple-d')
<?php
$statusNow = request()->input('status', '');
$userStatus = [
    0 => '<span class="label label-info">未审核</span>',
    1 => '<span class="label label-success">已通过</span>',
    2 => '<span class="label label-danger">未通过</span>',
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
                <div class="col-md-5 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-5 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x210114/index')}}">

                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="title"
                                   value="{{(request()->has('title')&&(request()->title!=''))?request()->title:''}}"
                                   class="form-control" size="16" placeholder="请输入作品标题">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择状态</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未审核</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已通过</option>
                                <option {{($statusNow =='2')?'selected':''}} value="2">未通过</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">查询</button>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <a href="{!! $exportUrl !!}" class="btn btn-default form-control">下载</a>--}}
{{--                        </div>--}}
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
                                    <th style="text-align: center;">总作品数</th>
                                    <th style="text-align: center;">标题</th>
                                    <th style="text-align: center;">作者</th>
                                    <th style="text-align: center;">正文</th>
                                    <th style="text-align: center;">图片</th>
                                    <th style="text-align: center;">状态</th>
                                    <th style="text-align: center;">上传时间</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65" src="{{$user->user->avatar}}" alt="">
                                            <div class="nickname">{{$user->user->nickname}}</div>
                                        </td>
                                            <td align="center">{{$user->user->submit_num}}</td>

                                        <td align="center">{{$user['title']}}</td>
                                        <td align="center">{{$user['auth']}}</td>
                                        <td align="center">{{$user['comment']}}</td>
                                        <td align="center" class="image">
                                            @if($user->image)
                                                <img class="tb-img" title="用户ID：{{$user->user->id}}"
                                                     src="{{$imgPrefix.$user['image']}}"
                                                     alt="">
                                            @endif
                                        </td>
                                            <td align="center" class="status">{!! $userStatus[$user['status']] !!}</td>
                                        <td align="center">{{$user['created_at']}}</td>
                                        <td align="center">
                                            @if($user->status == 0)
                                                <button type="button" class="btn btn-sm btn-default check"
                                                        data-id="{{$user->id}}">审核
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

        //拉黑
        $('.check').on('click', function (e) {
            var target = $(e.target);
            $userId = $(this).attr('data-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            var status = 0;
            layer.confirm('是否通过审核？', {
                btn: ['通过','不通过'] //按钮
            }, function(){
                $.post(
                    "{{route('x210114.check')}}",
                    {
                        id: $userId,
                        status: 1
                    },
                    function (res) {
                        if (res) {
                            target.parent().parent().find('.status:eq(0)').html('<span class="label label-success">已通过</span>');
                            target.parent().find('.check').remove();
                        }
                        layer.close(loading); //关闭loading
                        layer.msg('操作成功', {time: 2000});
                    }
                )
            }, function(){
                $.post(
                    "{{route('x210114.check')}}",
                    {
                        id: $userId,
                        status: 2
                    },
                    function (res) {
                        if (res) {
                            target.parent().parent().find('.status:eq(0)').html('<span class="label label-danger">未通过</span>');
                            target.parent().find('.check').remove();
                        }
                        layer.close(loading); //关闭loading
                        layer.msg('操作成功', {time: 2000});
                    }
                )
                });
            })
    </script>
@endsection
