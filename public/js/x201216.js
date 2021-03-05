/*
 *#################################################################################################################################
 *
 *    后台请求接口
 *    此文件依赖  jquery.js      | share authorization 方法 依赖 wx.bate.js |
 *
 *#################################################################################################################################
 */
/*ajax 请求统一处理*/
$.ajaxSetup({
    contentType: "application/x-www-form-urlencoded;charset=utf-8",
    /*请求前*/
    beforeSend: function () {

        // 这里加上loading 可根据需求自定义
        $.MyDialog.loading('正在处理');
    },
    /*请求完成*/
    complete: function (XMLHttpRequest, textStatus) {

        // 这里取消loading 可根据需求自定义
        $.MyDialog.destroy();
    },
    /*错误处理*/
    error: function (xhr, status, error) {
        switch (xhr.status) {
            // 验证出错
            case 422:
                //提交字段验证出错  错误信息为  xhr.responseJSON.error
                //处理错误信息
                $.MyDialog.error(xhr.responseJSON.error);
                break;
            case 500:
                //提交字段验证出错  错误信息为  xhr.responseJSON.error
                //处理错误信息
                $.MyDialog.error('服务器繁忙，请稍后再试!');
                break;
            default:
                //这里统一处理上面没有捕获的错误   可根据需求自定义
                $.MyDialog.error('服务器繁忙，请稍后再试!~~~');
                break;
        }
    }
});

var api = {
    /**
     *  是否开启调试模式
     */
    debug: true,

    /*
    * 分享页面 url
    * */
    shareUrl: '',
    /*
    * 路由地址对象
    * */
    urlObj: null,
    /*
    *  根路径
    * */
    baseUrl: 'https://wx.sanshanwenhua.com/vlvl/api/sswh',

    /**
     *   获取/记录 用户信息
     */
    user: function () {
        return new Promise(function (resolve, reject) {
            $.get(api.baseUrl + '/x201216/user',
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     * 提交报名
     */
    post: function (data) {
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x201216/post',
                /*接收参数-start*/
                {
                    'name': data.name,             //姓名
                    'mobile': data.mobile,         //手机号码
                    'company': data.company,       //所属公司[1:武汉生物制品研究所有限责任公司 2:国药集团武汉血制公司 3:武汉中生毓晋生物公司]
                    'zige': data.zige,             //是否具有购买资格[1:有 2:无]
                    'type': data.type,             //意向毛坯或装修房源[1:毛坯 2:装修]
                    'house': data.house,           //户型[1:99㎡ 2: 108㎡ 3: 115㎡ 4: 125㎡ 5: 133㎡]
                    'pay': data.pay,               //2成首付[1:是 2:否]
                },
                /*接收参数-end*/
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        })
    },

    /**
     * 验证
     */
    verify: function (code) {
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x201216/verify',
                /*接收参数-start*/
                {
                    'code': code,             //验证码
                },
                /*接收参数-end*/
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        })
    },
};
