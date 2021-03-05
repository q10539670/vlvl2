@extends('layouts.adminlte.simple-a')
<?php
$statusNow = request()->input('status', '');
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
                <div class="col-md-4 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">评论列表</h3>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x191014/comm')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="comm"
                                   value="{{(request()->has('comm')&&(request()->comm!=''))?request()->comm:''}}"
                                   class="form-control" size="16" placeholder="输入评论内容查询">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择评论状态</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">待审核</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">已通过</option>
                                <option {{($statusNow =='2')?'selected':''}} value="2">未通过</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">查询</button>
                        </div>
                        <div class="form-group">
                            <a href="{{url('vlvl/x191014/index')}}" class="btn btn-info form-control">主页</a>
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
                                    <th width="5%" align="center">序号</th>
                                    <th style="text-align: center;">头像/昵称</th>
                                    <th style="text-align: center;" width="40%">评论</th>
                                    <th style="text-align: center;" width="23%">图片</th>
                                    <th style="text-align: center;">评论时间</th>
                                    <th style="text-align: center;">状态</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $comm)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65" src="{{$comm->user->avatar}}" alt="">
                                            <div class="nickname">{{$comm->user->nickname}}</div>
                                        </td>
                                        <td align="center">{{$comm['comment']}}</td>
                                        <td align="center">
                                            <?php $images = explode('|', $comm['images']); ?>
                                            @if($images)
                                                <div id="layer-photos-demo" class="layer-photos-demo">

                                                    @foreach($images as $key => $img)
                                                        {!! $key%3==0?'<br>':'' !!}
                                                        @if($img)
                                                            <img class="tdr-avatar" width="65" height="50"
                                                                 layer-src="{{$prefix . $img}}" src="{{$prefix . $img}}"
                                                                 alt="">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td align="center">{{$comm['created_at']}}</td>
                                        <td align="center">
                                            @if($comm['status'] == 0)
                                                <a class="btn btn-info">待审核</a>
                                            @elseif($comm['status'] == 1)
                                                <a class="btn btn-success">已通过</a>
                                            @else()
                                                <a class="btn btn-danger">未通过</a>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <a class="btn btn-xs btn-success"
                                               onclick="pass(this,{{$comm['id']}})">通过</a><br>
                                            <a class="btn btn-xs btn-warning"
                                               onclick="refuse(this,{{$comm['id']}})">拒绝</a><br>
                                            <a class="btn btn-xs btn-danger"
                                               onclick="del(this,{{$comm['id']}})">删除</a>
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
        function pass(obj, id) {
            layer.confirm('确定通过吗', {icon: 3, title: '提示'}, function () {
                $.ajax({
                    url: '{{url('vlvl/x191014/verify')}}',
                    data: {id: id, status: 1, _token: '{{csrf_token()}}'},
                    type: 'post',
                    success: function (res) {
                        console.log(res)
                        if (res == 1) {
                            $(obj).parent().prev().children('a').removeClass('btn-info');
                            $(obj).parent().prev().children('a').removeClass('btn-danger');
                            $(obj).parent().prev().children('a').addClass('btn-success');
                            $(obj).parent().prev().children('a').html('已通过');
                            layer.msg('操作成功!', {icon: 1, time: 1000});
                        } else {
                            layer.msg('操作失败!', {icon: 5, time: 1000});
                        }
                    }
                })
            })
        }

        function refuse(obj, id) {
            layer.confirm('确定拒绝吗', {icon: 3, title: '提示'}, function () {
                $.ajax({
                    url: '{{url('vlvl/x191014/verify')}}',
                    data: {id: id, status: 2, _token: '{{csrf_token()}}'},
                    type: 'post',
                    success: function (res) {
                        if (res == 1) {
                            $(obj).parent().prev().children('a').removeClass('btn-info');
                            $(obj).parent().prev().children('a').removeClass('btn-success');
                            $(obj).parent().prev().children('a').addClass('btn-danger');
                            $(obj).parent().prev().children('a').html('未通过');
                            layer.msg('操作成功!', {icon: 1, time: 1000});
                        } else {
                            layer.msg('操作失败!', {icon: 5, time: 1000});
                        }
                    }
                })
            })
        }

        function del(obj, id) {
            layer.confirm('确定删除吗?', {icon: 3, title: '删除后无法找回,请谨慎!'}, function () {
                $.ajax({
                    url: '{{url('vlvl/x191014/del')}}',
                    data: {id: id, _token: '{{csrf_token()}}'},
                    type: 'delete',
                    success: function (res) {
                        if (res == 1) {
                            $(obj).parent().parent().empty();
                            layer.msg('操作成功!', {icon: 1, time: 1000});
                        } else {
                            layer.msg('操作失败!', {icon: 5, time: 1000});
                        }
                    }
                })
            })
        }

        //调用示例
        layer.photos({
            photos: '.layer-photos-demo'
            , anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
        });
    </script>
@endsection
