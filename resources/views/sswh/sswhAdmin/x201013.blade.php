@extends('layouts.adminlte.simple-d')
<?php
$statusNow = request()->input('status', '');
$statusLabel = [
    0 => '<span class="badge bg-info">未审核</span>',
    1 => '<span class="badge bg-green">通过</span>',
    2 => '<span class="badge bg-yellow">隐藏</span>',
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
    </style>
@endsection
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-7 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x201013/index')}}">

                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="">请选择是否审核</option>
                                <option {{($statusNow =='0')?'selected':''}}  value="0">未审核</option>
                                <option {{($statusNow =='1')?'selected':''}} value="1">通过</option>
                                <option {{($statusNow =='2')?'selected':''}} value="2">隐藏</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">查询</button>
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
                                    <th style="text-align: center;">书名</th>
                                    <th style="text-align: center;">状态</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center" id="{{$user['id']}}">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td>
                                            <img class="tdr-avatar" width="65" src="{{$user->user->avatar}}" alt="">
                                            <div class="nickname">{{$user->user->nickname}}</div>
                                        </td>
                                        <td align="center">{{$user['title']}}</td>
                                        <td align="center">{!! $statusLabel[$user['status']]??'未识别状态'!!}</td>
                                            
                                        <td align="center">
                                            @if($user['status'] ==0)
                                            <a class="btn  btn-success"
                                                              onclick="check(this,1,{{$user['id']}})"
                                                              href="javascript:;">通过</a>
                                            <a class="btn btn-danger"
                                               onclick="check(this,2,{{$user['id']}})"
                                               href="javascript:;">隐藏</a></td>
                                            @else
                                                <a class="btn  btn-info"
                                                   href="javascript:;">已审核</a>
                                                @endif
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
        //分类删除
        function check(obj,status, id) {
            layer.confirm('确定？', function () {
                // console.log(id);
                //发异步删除数据
                $.ajax({
                    url: '{{route('x201013.check')}}',
                    type: 'post',
                    data: {'_token': "{{csrf_token()}}",'status':status,'id':id},
                    success: function (res) {
                        console.log(res)
                        if (res) {
                            $(obj).parents("td").remove();
                            $('#'+id).append('<td align="center"><a class="btn btn-info" href="javascript:;">已审核</a></td>')
                            layer.msg('审核成功!', {icon: 1, time: 1000});
                        } else {
                            layer.msg('删除失败!', {icon: 5, time: 1000});
                        }
                    }
                })
            });
        }
    </script>
@endsection
