@extends('ticket.l191127.layout.app')

@section('css')
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            小票列表
            <small>进入黑名单的用户上传的小票将不会显示在以下表格</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('auth_v1/l181210/index')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="">小票管理</li>
            <li class="active">小票列表</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-lg-1">
                    <h3 class="box-title"></h3>
                </div>
                <div class="col-md-12 col-lg-10">
                    <form class="form-inline" id="searchOrDownload">
                        <div class="form-group">
                            <label class="sr-only" for=""></label>
                            <input type="text"
                                   name="user_id"
                                   size="32"
                                   value="{{request()->filled('user_id')?request()->user_id:''}}"
                                   class="form-control" placeholder="请输入用户id,openid或昵称模糊查询">
                        </div>
                        <div class="form-group">
                            <label class="" for="create"></label>
                            <input type="text"
                                   name="daterangepicker_created_at"
                                   size="38"
                                   value="{{request()->filled('daterangepicker_created_at')?request()->daterangepicker_created_at:''}}"
                                   class="form-control" size="" placeholder="请选择上传小票时间范围">
                        </div>
                        <div class="form-group">
                            <label class="" for="create"></label>
                            <input type="text"
                                   name="daterangepicker_checked_at"
                                   size="38"
                                   value="{{request()->filled('daterangepicker_checked_at')?request()->daterangepicker_checked_at:''}}"
                                   class="form-control" size="" placeholder="请选择审核小票的时间范围">
                        </div>
                        <div class="form-group">
                            <label></label>
                            <select class="form-control" name="check_status">
                                <option value="">请选择审核条件</option>
                                <option value="0" {{request()->filled('check_status')&&request()->check_status==0?'selected':''}}>
                                    未审核
                                </option>
                                <option value="-1" {{request()->filled('check_status')&&request()->check_status=='-1'?'selected':''}}>
                                    不通过(已审核)
                                </option>
                                <option value="1" {{request()->filled('check_status')&&request()->check_status=='1'?'selected':''}}>
                                    通过(已审核)
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label></label>
                            <select class="form-control" name="money">
                                <option value="">请选择中奖金额</option>
                                <option value="0" {{request()->filled('money')&&request()->money==0?'selected':''}}>
                                    未中奖
                                </option>
                                <option value="123" {{request()->filled('money')&&request()->money==123?'selected':''}}>
                                    1.23 元红包
                                </option>
                                <option value="168" {{request()->filled('money')&&request()->money==168?'selected':''}}>
                                    1.68 元红包
                                </option>
                                <option value="188" {{request()->filled('money')&&request()->money==188?'selected':''}}>
                                    1.88 元红包
                                </option>
                                <option value="288" {{request()->filled('money')&&request()->money==288?'selected':''}}>
                                    2.88 元红包
                                </option>
                                <option value="488" {{request()->filled('money')&&request()->money==488?'selected':''}}>
                                    4.88 元红包
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default form-control">查 询</button>
                        </div>
                        <div class="form-group">
                            <button type="button" title="可选择上传小票时间来导出" class="btn btn-default form-control" id="download">下 载</button>
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
                            <table class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    <th width="55" style="text-align: center;">序号
                                    </th>
                                    <th style="text-align: center;width:120px;">头像 / 昵称</th>
                                    <th width="60" style="text-align: center;">用户状态</th>
                                    {{--<th  style="text-align: center;">用户状态</td>--}}
                                    <th style="text-align: center;">图片</th>
                                    <th style="text-align: center;">审核状态</th>
                                    <th style="text-align: center;">中奖金额(元)</th>
                                    <th style="text-align: center;">上传时ip位置</th>
                                    <th style="text-align: center;width: 215px;">备注</th>
                                    <th width="60" style="text-align: center;">上传时间</th>
                                    <th width="60" style="text-align: center;">审核时间</th>
                                    <th style="text-align: center;">操作</th>
                                </tr>
                                </thead>
                                <tbody id="ticket-tb">
                                @foreach($paginator as $ticket)
                                    <tr align="center" id="{{$ticket->id}}">
                                        <?php $index = $paginator->perPage() * ($paginator->currentPage() - 1) ?>
                                        <td align="center">{{$index+$loop->iteration}}</td>
                                        <td align="center" >
                                            <img class="tdr-avatar" title="点击查看用户" data-user-id="{{$ticket['user']['id']}}" width="85" src="{{$ticket['user']['avatar']}}" alt="">
                                            <div class="nickname">{{$ticket['user']['nickname']}}</div>
                                        </td>
                                        <td align="center" class="status">{!! $userStatus[$ticket['user']['status']] !!}</td>
                                        {{--                                    <td align="center">{!! $userStatus[$ticket['user']['status']] !!}</td>--}}
                                        <td align="center">
                                            <img class="tb-img" title="小票ID：{{$ticket['id']}}" src="{{env('APP_URL').'/storage/'.$ticket['img_url']}}"
                                                 alt="">
                                        </td>
                                        <td class="check_status" align="center">
                                            {!! $checkStatusLabel[$ticket['check_status']] !!}
                                        </td>
                                        <td class="money" align="center">
                                            {!! $moneyLabel[$ticket['money']] !!}
                                        </td>
                                        <td class="money" align="center">
                                            {!! str_replace("/",'<br />',$ticket['location']) !!}
                                        </td>
                                        <td class="describle" align="center">
                                            <div class="p-describle">{{$ticket['describle']}}</div>
                                            @if($ticket['send_listid'])
                                                <div class="center-block success-discrible">微信红包订单号:<br />{{$ticket['send_listid']}}</div>
                                            @endif
                                        </td>
                                        <td align="center">{{$ticket['created_at']}}</td>
                                        <td class="checked_at" align="center">{{$ticket['checked_at']}}</td>
                                        <td align="center">
                                            @if($ticket->check_status==0)
                                                <button type="button" class="btn  btn-success tongguo">通 过</button>
                                                <br/>
                                                <button type="button" class="btn  btn-warning butongguo">不通过</button>
                                            @else
                                                <button type="button" class="btn  btn-info disabled finish">已完成审核</button>
                                                @if($ticket->money ==0)
                                                    <br />
                                                    <button type="button" class="btn  btn-danger check-reset">重 置</button>
                                                @endif
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
    <!--  审核不通过 模态框  -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">请选择或输入审核不通过原因</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input type="radio" name="errMsg" class="check_err" value="图片不是合格的汤达人购物小票" checked>
                                <span>图片不是合格的汤达人购物小票</span>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="errMsg" class="check_err"  value="图片日期和上传日期不同">
                                <span>图片日期和上传日期不同</span>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="errMsg" class="check_err"  value="图片不清晰">
                                <span>图片不清晰</span>
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" id="check-other" name="errMsg" id="optionsRadios2" value="0" />
                                <span>其他</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group" style="display:none;">
                        <textarea class="form-control" name="errMsgImport" id="errMsgImport" rows="3" value="" placeholder="请输入"></textarea>
                    </div>
                    <input type="hidden" id="ticket_id" name="ticket_id" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" id="sub-check-err" class="btn btn-primary">提交</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection