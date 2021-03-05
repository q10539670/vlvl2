
/*
* example
* <div class="form-group" >
    <label class="sr-only" for=""></label>
    <input type="text"
           name="dateRangePicker"
           {{--value=""--}}
           value="2018-11-02 00:00:00 至 2018-11-23 23:59:59"
           size="38"
           class="form-control" placeholder="请选择日期范围">
</div>
*
* */
//选择时间
$('input[name^="daterangepicker"]').daterangepicker({
    // autoApply: true,
    autoUpdateInput: false,  //不自动填充
    timePicker: true, //显示时间
    timePicker24Hour: true, //时间制
    timePickerSeconds: true, //时间显示到秒
    // startDate: moment().format('YYYY-MM-DD 00:00:00'), //设置开始日期
    // endDate: moment().format('YYYY-MM-DD 23:59:59'), //设置结束器日期
    // maxDate: moment(new Date()), //设置最大日期
    "opens": "center",
    ranges: {
        '今天': [moment().format('YYYY-MM-DD 00:00:00'), moment().format('YYYY-MM-DD 23:59:59')],
        '昨天': [moment().subtract(1, 'days').format('YYYY-MM-DD 00:00:00'), moment().subtract(1, 'days').format('YYYY-MM-DD 23:59:59')],
        '上周': [moment().week(moment().week() - 1).startOf('week').add(1, 'd').format('YYYY-MM-DD 00:00:00'),
            moment().week(moment().week() - 1).endOf('week').add(1, 'd').format('YYYY-MM-DD 23:59:59')],
        // '上周': [moment().subtract(6, 'days').format('YYYY-MM-DD 00:00:00'), moment().format('YYYY-MM-DD 23:59:59')],
        // '本月': [moment().startOf('month'), moment().endOf('month')],
        // '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    showWeekNumbers: true,
    locale: {
        "format": "YYYY-MM-DD HH:mm:ss",
        "separator": " 至 ",
        "applyLabel": "确定",
        "cancelLabel": "清空",
        "fromLabel": "起始时间",
        "toLabel": "结束时间'",
        "customRangeLabel": "自定义",
        "weekLabel": "W",
        "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
        "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        "firstDay": 1
    },

}, function (start, end, label) {
    // timeRangeChange = [start.format('YYYY-MM-DD HH:mm:ss'), end.format('YYYY-MM-DD HH:mm:ss')];
    // console.log(timeRangeChange);
    if(!this.autoUpdateInput)this.autoUpdateInput=true; // 开启自动填充
}).on('cancel.daterangepicker', function (ev, picker) {
    //取消按钮回调
    $(this).val("");
}).on('apply.daterangepicker', function (ev, picker) {
    //确定按钮 回调
});
// $('input[name="datePicker"]').val('');