@extends('ticket.l191127.layout.app')

@section('css')
@endsection

@section('style')
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

        #ticket-tb th {
            vertical-align: middle;
        }

        #ticket-tb td {
            vertical-align: middle;
        }

        .tongguo {
            margin-bottom: 8px;
        }

        .tdr-avatar {
            max-width: 100%;
            padding: 4px;
            border-radius: 4px;
            border: 1px solid silver;
            margin-bottom: 4px;
        }
    </style>
@endsection

@php
    $userStatus = [
        1=>'<span class="label label-info">正常</span>',
        2=>'<span class="label label-danger">禁用</span>',
    ];
    $request = request();
    $sortKey = $request->sort_key;
    $sortOrderShow = $request->sort_order??'desc';
    $sortOrderRedirect =  $request->sort_order=='asc'?'desc':'asc';
@endphp

@section('content-header')
    <section class="content-header">
        <h1>
            用户列表
            {{--            <small>进入黑名单的用户上传的小票将不会显示在以下表格</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="">用户管理</li>
            <li class="active">小票列表</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-1 col-md-2 col-lg-3">
                    <h3 class="box-title"></h3>
                </div>
                <div class="col-sm-10 col-md-8 col-lg-6">
                    <form class="form-inline">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3"></label>
                            <input type="text"
                                   name="user_id"
                                   size="32"
                                   value="{{(request()->has('user_id')&&(request()->user_id!=''))?request()->user_id:''}}"
                                   class="form-control" placeholder="请输入用户id或昵称模糊查询">
                        </div>
                        <div class="form-group">
                            <label></label>
                            <select class="form-control" name="status">
                                <option value="">请选择条件</option>
                                <option value="1" {{request()->filled('status')&&request()->status=='1'?'selected':''}}>
                                    正常用户
                                </option>
                                <option value="2" {{request()->filled('status')&&request()->status=='2'?'selected':''}}>
                                    黑名单用户
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default form-control">查询</button>
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
                            <table class="table table-bordered table-hover dataTable" id="ticket-tb">
                                <thead>
                                <tr>
                                    <th width="55" style="text-align: center;">序号</th>
                                    <th width="50" style="text-align: center;">用户id</th>
                                    <th style="text-align: center;width:120px;">头像<br/>昵称</th>
                                    <th width="60" style="text-align: center;">状态</th>
                                    <th style="text-align: center;">图片</th>
                                    <th
                                            class="sorting{{($sortKey=='upload_num')?'_'.$sortOrderShow:''}}"
                                            data-url="{{action('\App\Http\Controllers\Ticket\L191127\UserController@index',
                                        array_merge(request()->all(),['sort_key'=>'upload_num', 'sort_order'=>$sortKey=='upload_num'?$sortOrderRedirect:'desc']))}}"
                                            onclick="return location.href=$(this).attr('data-url');"
                                            width="65" style="text-align: center;">
                                        累计上传<br/>总次数
                                    </th>
                                    <th
                                            class="sorting{{($sortKey=='prize_num')?'_'.$sortOrderShow:''}}"
                                            data-url="{{action('\App\Http\Controllers\Ticket\L191127\UserController@index',
                                        array_merge(request()->all(),['sort_key'=>'prize_num', 'sort_order'=>$sortKey=='prize_num'?$sortOrderRedirect:'desc']))}}"
                                            onclick="return location.href=$(this).attr('data-url');"
                                            width="65" style="text-align: center;">
                                        累计中奖<br/>总次数
                                    </th>
                                    <th
                                            class="sorting{{($sortKey=='total_money')?'_'.$sortOrderShow:''}}"
                                            data-url="{{action('\App\Http\Controllers\Ticket\L191127\UserController@index',
                                        array_merge(request()->all(),['sort_key'=>'total_money', 'sort_order'=>$sortKey=='total_money'?$sortOrderRedirect:'desc']))}}"
                                            onclick="return location.href=$(this).attr('data-url');"
                                            width="70" style="text-align: center;">
                                        累计中奖<br/>总金额(元)
                                    </th>
                                    <th
                                            class="sorting{{($sortKey=='created_at')?'_'.$sortOrderShow:''}}"
                                            data-url="{{action('\App\Http\Controllers\Ticket\L191127\UserController@index',
                                        array_merge(request()->all(),['sort_key'=>'created_at', 'sort_order'=>$sortKey=='created_at'?$sortOrderRedirect:'desc']))}}"
                                            onclick="return location.href=$(this).attr('data-url');"
                                            width="65" style="text-align: center;">
                                        注册时间
                                    </th>
                                    <th
                                            class="sorting{{($sortKey=='updated_at')?'_'.$sortOrderShow:''}}"
                                            data-url="{{action('\App\Http\Controllers\Ticket\L191127\UserController@index',
                                        array_merge(request()->all(),['sort_key'=>'updated_at', 'sort_order'=>$sortKey=='updated_at'?$sortOrderRedirect:'desc']))}}"
                                            onclick="return location.href=$(this).attr('data-url');"
                                            width="65" style="text-align: center;">
                                        最近一次<br/>活跃时间
                                    </th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginator as $user)
                                    <tr align="center">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td align="center">{{$user['id']}}</td>
                                        <td align="center">
                                            <img class="tdr-avatar" width="85" src="{{$user['avatar'] ?:'https://wx.sanshanwenhua.com/statics/images/common/timg.jpg'}}" alt="">
                                            <div class="nickname">{{$user['nickname']}}</div>
                                        </td>
                                        <td align="center" class="status">{!! $userStatus[$user['status']] !!}</td>
                                        <td align="center">
                                            <div class="galley" style="width: 395px;">
                                                <ul class="" style="list-style: none;">
                                                    @foreach($user['tickets'] as $ticket)
                                                        <li style="float: left;margin-right: 8px;">
                                                            <img class="tb-img
                                                        @if($loop->index>2)
                                                                    hidden
                                                        @endif"
                                                                 src="{{env('APP_URL').'/storage2/'.$ticket['img_url']}}"
                                                                 alt="{{'小票ID: '.$ticket['id'].'  |状态：'.$checkStatus[$ticket['check_status']]
                                                         .'<br />'
                                                         .'金额：'.($ticket['money']/100).'元'
                                                         .'<br />'
                                                         .'上传时间：'.$ticket['created_at']
                                                         .'<br />'
                                                         .$ticket['describle'].'<br />'}}"/>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                        <td align="center">{{$user['upload_num']}}</td>
                                        <td align="center">{{$user['prize_num']}}</td>
                                        <td align="center">{{$user['total_money']/100}}</td>
                                        <td align="center">{{$user['created_at']}}</td>
                                        <td align="center">{{$user['updated_at']}}</td>
                                        <td align="center">
                                            @if($user->status ==1)
                                                <button type="button" data-id="{{$user->id}}"
                                                        class="btn btn-danger heimingdan">加入黑名单
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
        /*处理分页跳转*/
        $('#page-jump').click(function () {
            var pageIndex = $('#page-index').val();
            var pageLis = $('#pagination-li-s li');
            var maxPage = parseInt(pageLis.eq(pageLis.length - 3).text());
            if (!pageIndex || parseInt(pageIndex) < 0 || pageIndex > maxPage) {
                return false;
            }
            var baseUrl = location.hostname + location.pathname;
            if (/\?/.test(location.href)) {
                if (/page=\d*/.test(location.href)) {
                    location.href = location.href.replace(/page=\d*/, 'page=' + pageIndex);
                } else {
                    location.href = location.href + '&page=' + pageIndex;
                }
            } else {
                location.href = location.href + '?page=' + pageIndex;
            }
        })
        //图片点击放大
        $('.tb-img').on('click', function (e) {
            var galley = $(e.target).parents('.galley:eq(0)')[0];
            var viewer = new Viewer(galley, {
                url: 'src',
                // toolbar: {
                //     oneToOne: true,
                //
                //     prev: function() {
                //         viewer.prev(true);
                //     },
                //
                //     play: true,
                //
                //     next: function() {
                //         viewer.next(true);
                //     },
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
        //加入黑名单
        $('.heimingdan').on('click', function (e) {
            if (!confirm('您确认拉黑该用户吗？')) {
                return false;
            }
            loading = layer.load(1, {
                shade: [0.1, '#fff'] //0.1透明度的白色背景
            });
            //提交请求
            $.post("{{route('l191127.user.blankList')}}",
                {user_id: $(e.target).attr('data-id')},
                function (res) {
                    layer.close(loading); //关闭loading
                    layer.msg(res.message, {time: 2000});
                    $(e.target).parent().parent().find('.status:eq(0)').html('<span class="label label-danger">禁用</span>');
                    $(e.target).parent().html('');
                });
        });
    </script>
@endsection
