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
            $.get(api.baseUrl + '/x191206/user',
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     *   提交信息
     */
     // card  卡片编号1-9
     //     1-8   一一对应:开业倒计时一周年 8张卡
     //     9     自己写的那张卡
    choose: function (data) {
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x191206/choose',
                /*接收参数-start*/
                {
                    card: data.card,              //卡片编号
                    card_words: data.card_words,  //卡片文字
                },
                /*接收参数-end*/
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     *   提交信息
     */
    post: function (data) {
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x191206/post',
                /*接收参数-start*/
                {
                    name: data.name,              //姓名
                    phone: data.phone,            //电话
                    address:data.address,         //地址
                },
                /*接收参数-end*/
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     * 分享
     */
    //地址栏后面拼接上 target_user_id 参数和值
    share: function () {
        var targetUserId = 0;
        if (api.parseUrl().params.target_user_id != undefined) {
            targetUserId = api.parseUrl().params.target_user_id;
        }
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x191206/share',
                /*接收参数-start*/
                {
                    target_user_id: targetUserId,   //目标ID
                },
                /*接收参数-end*/
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     * 点赞
     */
    likes: function (targetUserId) {
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x191206/likes',
                /*接收参数-start*/
                {
                    target_user_id: targetUserId,   //目标ID
                },
                /*接收参数-end*/
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     * 抽奖
     */
    prize: function () {
        return new Promise(function (resolve, reject) {
            $.post(api.baseUrl + '/x191206/prize',
                function (res) {
                    api.debug && console.log(res);
                    resolve(res);
                });
        });
    },

    /**
     * 上传海报
     */
    upload:  function (data) {
        var formData = new FormData();
        formData.append('img', data);
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: api.baseUrl + '/l191206/upload',
                type:"post",
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res);
                    resolve(res);
                }
            });
        });
    },

    /**
     * 解析url  辅助函数 不是请求
     */
    parseUrl: function () {
        if (api.urlObj) {
            return api.urlObj;
        }
        var url = window.location.href.indexOf('#') > -1 ? window.location.href.split('#')[0].replace(/^(http|https):\/\//i, '') :
            window.location.href.replace(/^(http|https):\/\//i, '');
        var urlStr;
        var params = {};
        if (url.indexOf('?') > -1) {
            urlStr = url.split('?')[0];
            var paramsArr = url.split('?')[1].split('&');
            for (var i = 0; i < paramsArr.length; i++) {
                params[paramsArr[i].split('=')[0]] = paramsArr[i].split('=')[1];
            }
        } else {
            urlStr = url;
        }
        api.urlObj = {
            domain: urlStr.split('/')[0], // 域名
            itemName: urlStr.split('/')[2], //项目名称
            fileName: urlStr.split('/')[3], // 文件名称
            params: params, //参数
        };
        return api.urlObj;
    },
};
