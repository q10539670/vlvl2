@extends('layouts.adminlte.simple-b-qwt')

@section('title', $title)

@section('nav')
    <li class="">
        <!-- Menu toggle button -->
{{--        <a href="{{url('sspc/l190617a/index')}}" title="">--}}
{{--        <a href="{{url('sspc/l190617a/index')}}" title="">  --}}
        <a href="{{$indexUrl}}" title="">
            用户列表
        </a>
    </li>
    <li class="active disabled">
        <!-- Menu toggle button -->
        <a href="#" title="">
            地区统计
        </a>
    </li>
@endsection

@section('css')
    <link href="https://cdn.bootcss.com/bootstrap-daterangepicker/2.1.25/daterangepicker.css" rel="stylesheet">
    <style>
        .qwt-avatar {
            border: 1px solid silver;
            border-radius: 3px;
            padding: 5px;
            width: 75px;
            height: 75px;
        }

        #qwt-tb th {
            vertical-align: middle;
        }

        #qwt-tb td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="box">
        <div id="main" style="width: 1000px;height:1000px;"></div>
    </div>
    <div class="box">
        <div id="main2" style="width: 1000px;height:600px;"></div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="box-title">湖北用户统计表</h3>
                    <span style="margin-left: 5px;color: #afb2b5;">统计时间{{$updated_at}} (刷新间隔：5分钟)</span>
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
                        <table class="table table-bordered table-hover dataTable" id="qwt-tb">
                            <thead>
                            <tr>
                                <th width="35" rowspan="2" align="center">序号</th>
                                <th style="text-align: center;" rowspan="2">分公司</th>
                                <th style="text-align: center;" colspan="3">答题活动参与率(完成答题并抽奖)</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">参与目标量</th>
                                <th style="text-align: center;">完成量</th>
                                <th style="text-align: center;">完成占比</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hubei_task as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td style="text-align: center;">{{$v['name']}}</td>
                                    <td style="text-align: center;">{{$v['targetNum']}}</td>
                                    <td style="text-align: center;">{{$v['currentNum']}}</td>
                                    <td style="text-align: center;">{{$v['v']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">

                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@section('js')
    <script src="{{asset('/vlvl/js/echarts.min.js')}}"></script>
    <script src="{{asset('/vlvl/js/admin.js')}}"></script>
    <script>
        var myChart = echarts.init(document.getElementById('main'));
        option = {
            title: {
                text: '全国省级参与量柱状图',
                subtext: '统计时间{{$updated_at}} (刷新间隔：5分钟)'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: []
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            backgroundColor: '#fff',
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: {!! $quanguo['name'] !!}
            },
            series: [
                {
                    name: '',
                    type: 'bar',
                    label: {
                        normal: {
                            show: true,
                            position: 'right'
                        }
                    },
                    data: {!! $quanguo['value'] !!}
                },
            ]
        };
        myChart.setOption(option);
    </script>
    <script>
        // 基于准备好的dom，初始化echarts实例
        var myChart2 = echarts.init(document.getElementById('main2'));
        option2 = {
            title: {
                text: '湖北地区参与量柱状图',
                subtext: '统计时间{{$updated_at}} (刷新间隔：5分钟)'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: []
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            backgroundColor: '#fff',
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: {!! $hubei['name'] !!}
            },
            series: [
                {
                    name: '',
                    type: 'bar',
                    label: {
                        normal: {
                            show: true,
                            position: 'right'
                        }
                    },
                    data: {{$hubei['value']}}
                },
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart2.setOption(option2);
    </script>
@endsection
