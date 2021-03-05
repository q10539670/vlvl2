@extends('layouts.adminlte.simple-a')

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
                    <form class="form-inline" method="get" action="{{url('vlvl/x200806/index')}}">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="nameOrPhone"
                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"
                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">
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
                                    {{--                                    <th style="text-align: center;">头像/昵称</th>--}}
                                    <th style="text-align: center;">姓名</th>
                                    <th style="text-align: center;">电话</th>
                                    {{--                                    <th style="text-align: center;">上传次数</th>--}}
                                    <th style="text-align: center;">上传照片</th>
                                    <th style="text-align: center;">参与时间</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $images)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        {{--                                        <td>--}}
                                        {{--                                            <img class="tdr-avatar" width="65" src="{{$images->user->avatar}}" alt="">--}}
                                        {{--                                            <div class="nickname">{{$images->user->nickname}}</div>--}}
                                        {{--                                        </td>--}}
                                        <td align="center">{{$images->user->name}}</td>
                                        <td align="center">{{$images->user->phone}}</td>
                                        {{--                                        <td align="center">{{$images->user->img_upload_num}}</td>--}}
                                        <td align="center">
                                            <img class="tb-img"
                                                 src="{{'https://cdnn.sanshanwenhua.com/statics/'.$images->images}}">
                                        </td>
                                        <td align="center">{{$images['created_at']}}</td>
                                        <td align="center">
                                            <button type="button" data-id="{{$images->id}}"
                                                    class="btn btn-danger delete">删除
                                            </button>
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
        $('.delete').on('click', function (e) {
            layer.confirm('确定删除吗？',
                function () {
                    loading = layer.load(1, {
                        shade: [0.1, '#fff'] //0.1透明度的白色背景
                    });
                    $.post("{{url('vlvl/x200806/delete')}}",
                        {id: $(e.target).attr('data-id')},
                        function (res) {
                            layer.close(loading); //关闭loading
                            if (res.code === 1) {
                                layer.msg(res.message, {time: 2000});
                                $(e.target).parent().parent().remove();
                            } else {
                                layer.msg(res.message, {time: 2000});
                            }
                        }
                    );
                }
            )
        });
        $(document).ready(function () {
        }).keydown(
            function (e) {

                if (e.which === 27) {

                    layer.closeAll();

                }

            });
    </script>
@endsection