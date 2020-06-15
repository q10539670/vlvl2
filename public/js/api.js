/*
 *#################################################################################################################################
 *
 *    后台请求接口
 *    此文件依赖  jquery.js      | share authorization 方法 依赖 wx.bate.js |
 *
 *#################################################################################################################################
 */
var api = {
    /*
     *  是否开启调试模式
     */
    debug: true,
    /*
     * 统一说明
     * */
    baseUrl: 'https://wx.sanshanwenhua.com/backend/vlls/public/api/sswh',
    /*
    用户刚进入页面处理 返回用户信息 res.data.user
    */
    user: function(data) {
        $.get(api.baseUrl + '/l180911a/user', function(res) {
            api.debug && console.log(res);
            if (data && data.callback) return typeof data.callback == 'function' && data.callback(res);
        });
    },

    /*报名
     * 接收参数 {name:姓名, phone:电话, callback:function(){}}
     * */
    reg: function(data) {
        $.post(api.baseUrl + '/l180911a/reg', { name: data.name, phone: data.phone }, function(res) {
            api.debug && console.log(res);
            if (data && data.callback) return typeof data.callback == 'function' && data.callback(res);
        });
    },
    /*分享
     * 接收参数 {callback:function(){}}
     * */
    share: function(data) {
        $.get(api.baseUrl + '/l180911a/share', { name: data.name, phone: data.phone }, function(res) {
            api.debug && console.log(res);
            if (data && data.callback) return typeof data.callback == 'function' && data.callback(res);
            // if(res.code ==1){
            //     // 答题次数+1
            // }
            // else if(res.code==2){
            //     //  次数+1已用完 本次不加答题次数
            // }
        });
    },
    /*答题
     * 接收参数 {trueNum:答对题的数量,callback:function(){}}
     *
     * */
    ans: function(data) {
        $.post(api.baseUrl + '/l180911a/ans', { name: data.name, phone: data.phone }, function(res) {
            api.debug && console.log(res);
            if (data && data.callback) return typeof data.callback == 'function' && data.callback(res);
            // if(res.code ==1){
            //     //提交有效-数据更新成功
            // }
            // else if(res.code ==2){
            //     //无效提交-已答对6道题或以上，无法重复提交
            // }
            // else if(res.code ==3){
            //     //无效提交-今天已答题次数已用完
            // }
        });
    },
    /*
     * 解析url  辅助函数 不是请求
     */
    parseUrl: function() {
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
        return {
            domain: urlStr.split('/')[0], // 域名
            itemName: urlStr.split('/')[2], //项目名称
            fileName: urlStr.split('/')[3], // 文件名称
            params: params, //参数
        };
    },

};
