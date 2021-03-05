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
                <div class="col-md-4 col-sm-2">
                    <div class="form-group">
                        <h3 class="box-title">用户列表</h3>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2 col-sm-8 col-sm-offset-2">
                    <form class="form-inline" method="get" action="{{url('vlvl/x200120/index')}}">
                        {{--                        <div class="form-group">--}}
                        {{--                            <label class="sr-only" for="exampleInputEmail3"></label>--}}
                        {{--                            <input type="text"--}}
                        {{--                                   name="nameOrPhone"--}}
                        {{--                                   value="{{(request()->has('nameOrPhone')&&(request()->nameOrPhone!=''))?request()->nameOrPhone:''}}"--}}
                        {{--                                   class="form-control" size="16" placeholder="请输入姓名或手机号查询">--}}
                        {{--                        </div>--}}
                        {{--                        <div class="form-group">--}}
                        {{--                            <select name="order" class="form-control">--}}
                        {{--                                <option value="">请选择排序方式</option>--}}
                        {{--                                <option {{($statusNow =='0')?'selected':''}}  value="0">按人气</option>--}}
                        {{--                                <option {{($statusNow =='1')?'selected':''}} value="1">按最新</option>--}}
                        {{--                            </select>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="form-group">--}}
                        {{--                            <button type="submit" class="btn btn-primary form-control">查询</button>--}}
                        {{--                        </div>--}}
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
                                    <th style="text-align: center;">用户ID</th>
                                    <th style="text-align: center;">上传次数</th>
                                    <th style="text-align: center;">照片</th>
                                    <th style="text-align: center;">参与时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <th style="text-align: center;">{{$user['id']}}</th>
                                        <td>{{$user['upload_num']}}</td>
                                        <td align="center" class="image">
                                            <div class="galley" {{--style="width: 395px;"--}}>
                                                <ul class="" style="list-style: none;">
                                                    @if($user->images)
                                                        <?php $images = explode('|', $user->images) ?>
                                                        @foreach($images as $key => $image)
                                                            <li style="float: left;margin-right: 8px;">
                                                                <img class="tb-img
                                                        @if($loop->index>4)
                                                                    hidden
@endif"
                                                                     src="{{$imgPrefix.$image}}"/>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
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
        //图片点击放大
        $('.tb-img').on('click', function (e) {
            var galley = $(e.target).parents('.galley:eq(0)')[0];
            var viewer = new Viewer(galley, {
                url: 'src',
                // toolbar: {
                //     // oneToOne: true,
                //     //
                //     // prev: function() {
                //     //     viewer.prev(true);
                //     // },
                //     //
                //     // play: true,
                //     //
                //     // next: function() {
                //     //     viewer.next(true);
                //     // },
                //
                //     download: function() {
                //         const a = document.createElement('a');
                //
                //         a.href = viewer.image.src;
                //         a.download = viewer.image.alt;
                //         document.body.appendChild(a);
                //         a.click();
                //         document.body.removeChild(a);
                //     },
                // },
                hidden: function () {
                    viewer.destroy();
                },
            });
        });

        $('.delete').on('click', function (e) {
            if (!confirm('您确认删除吗？')) {
                return false;
            }
            var target = $(e.target);
            $userId = $(this).attr('data-id');
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            $.post("{{route('x200115.delete')}}",
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
    </script>
@endsection
