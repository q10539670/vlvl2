/*********************
 * ssshare.js 微信分享
 * Author wcfan
 * LastUpdateDate 2020/07/24 16:00
 ************************/
 
(function(){
	"use strict";
	var ssshare = {
		urlshare:"https://wx.sanshanwenhua.com/vlvl/api/common/share", //分享api接口地址
		urlauth:"https://wx.sanshanwenhua.com/vlvl/api/common/auth", //授权api接口地址,
		urlgetappid:"https://wx.sanshanwenhua.com/vlvl/api/common/app_id", //获取appid接口地址
		urlwxview:"https://wx.sanshanwenhua.com/vlvl/api/common/view", //纪录浏览量分享量接口地址
		getQueryString:function(name){
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		    var r = window.location.search.substr(1).match(reg);
		    if (r != null) return decodeURIComponent(r[2]); return "";
		},
		promiseAjax:function(opts){
			var type = opts.type ? opts.type : "post",dataType = opts.dataType ? opts.dataType : "json",data = opts.data || {},async = opts.async ? opts.async : false,
				cache = opts.cache ? opts.cache : false,contentType = "application/x-www-form-urlencoded; charset=UTF-8",url = opts.url,close = opts.close;
			data.random = Date.parse(new Date());
			data.factory = "sswh";
			
			return new Promise(function(resolve, reject){
				$.ajax({
			        url: url,
			        type: type,
			        dataType: dataType,
			        data: data,
			        async: async,
			        cache: cache,
			        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
				
			        success: function(text) {
			        	if(close) close();
			        	resolve(text);
			        },
			        error:function(err){
			        	if(close) close();
			        	reject(err);
			        }
			    });
			});
		},
		isPC:function(){
			if (navigator.userAgent.indexOf("Windows NT") !== -1) {
				return;
			}
		},
		initAppid:function(appname){
			var that = this;
			var promise1 = that.promiseAjax({
				url:that.urlgetappid,
				data:{method:"getappid",appname:appname},
				dataType:"json"
			});
			return promise1;
		},
		initWeixin:function(opts){
			var that = this,appname = opts.appname,callback = opts.callback,code = that.getQueryString("code");
			that.isPC();
			that.promiseAjax({
				url:that.urlauth,
				data:{method:"wxauth",appname:appname,auth:"wx",code:code}
			}).then(function(response2){
				var s = setTimeout(function(){
					clearTimeout(s);
					s = null;
					if(response2.message == 'success'){
						if(callback) callback(response2);
					}else{
						that.authUrl();
					}
				},200);
			});
		},
		initWeixinAuth:function(opts){
			var that = this,appname = opts.appname,callback = opts.callback,code = that.getQueryString("code");
			that.isPC();
			that.promiseAjax({
				url:that.urlauth,
				data:{method:"wxauth",appname:appname,auth:"wxauth",code:code}
			}).then(function(response2){
				var s = setTimeout(function(){
					if(response2.message == 'success'){
						if(callback) callback(response2);
					}else{
						that.authUrl2();
					}
				},200);
			});
		},
		authUrl:function(){
			var url = location.href,myUrl = (url.indexOf('#') > -1) ? url.split('#')[0] : url,that = this;
			myUrl = that.removeParm(myUrl,"code");
			myUrl = that.removeParm(myUrl,"state");
			myUrl = that.removeParm(myUrl,"paras");
			myUrl = that.removeParm(myUrl,"sign");
			myUrl = that.removeParm(myUrl,"appId");
			var jumpUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+that.appid+"&redirect_uri=" + encodeURIComponent(myUrl) + "&response_type=code&scope=snsapi_userinfo&state=whmozi#wechat_redirect";
			var s = setTimeout(function(){
				clearTimeout(s);
				s = null;
				window.location.replace(jumpUrl);
			},200);
		},
		authUrl2:function(){
			var url = location.href,myUrl = (url.indexOf('#') > -1) ? url.split('#')[0] : url,that = this;
			myUrl = that.removeParm(myUrl,"code");
			myUrl = that.removeParm(myUrl,"state");
			myUrl = that.removeParm(myUrl,"paras");
			myUrl = that.removeParm(myUrl,"sign");
			myUrl = that.removeParm(myUrl,"appId");
			var jumpUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+that.appid+"&redirect_uri=" + encodeURIComponent(myUrl) + "&response_type=code&scope=snsapi_base&state=whmozi#wechat_redirect";
			var s = setTimeout(function(){
				clearTimeout(s);
				s = null;
				window.location.replace(jumpUrl);
			},200);
		},
		removeParm:function(url,name){
			var reg=new RegExp("[\?|&]+[\s]*("+name+"[\s]*=[^&^#]+[&]?)","i");
			if(reg.test(url)){
				var result = reg.exec(url);
				if(result && result[0]){
					if(result[0].indexOf('?') > -1){
						if(result[1].indexOf('&') > -1) return url.replace(result[0],"?");
						return url.replace(result[0],"");
					}else if(result[1].indexOf('&') > -1){
						return url.replace(result[0],"&");
					}else{
						return url.replace(result[0],"");
					}
				}
			}
			return url;
		},
		init:function(opts){
			var link = opts.link,pic = opts.imgUrl,title = opts.title,desc = opts.desc,callback = opts.success,
				title2 = (opts.title2) ? opts.title2 : opts.title,hideOptionMenu = opts.hideOptionMenu,getNetworkType = opts.getNetworkType,that = this,authType = opts.authType ? opts.authType : "base",authFun = opts.authFun,appname = opts.appname ? opts.appname : "";
			that.initAppid(appname).then(function(response){
				that.appid = response.data.appid;
				that.promiseAjax({ //get share sign
					url:that.urlshare,
					data: { url: encodeURIComponent(location.href),method:"getsign",appname:appname }
				}).then(function(response){
					wx.config({
						debug: false,
						appId: response.data.signPackage.appId,
						timestamp: response.data.signPackage.timestamp,
						nonceStr: response.data.signPackage.nonceStr,
						signature: response.data.signPackage.signature,
						jsApiList: [
							'onMenuShareTimeline',
							'onMenuShareAppMessage',
							'startRecord',
							'stopRecord',
							'onVoiceRecordEnd',
							'playVoice',
							'pauseVoice',
							'stopVoice',
							'onVoicePlayEnd',
							'uploadVoice',
							'downloadVoice',
							'onVoicePlayEnd',
							'chooseImage',
							'uploadImage',
							'translateVoice',
							'getLocation',
							'openLocation',
							'hideOptionMenu',
							'hideAllNonBaseMenuItem',
							'scanQRCode',
							'closeWindow',
							'updateTimelineShareData',
							'updateAppMessageShareData'
						]
					});
				});
				
				if(authFun){ //开启授权
					if(authType == 'base'){ //静默授权
						that.initWeixin({
							appname:appname,
							callback:function(authres){
								if(authFun) authFun(authres);
							}
						})
					}else if(authType == 'userinfo'){ //获取用户信息授权
						that.initWeixinAuth({
							appname:appname,
							callback:function(authres){
								if(authFun) authFun(authres);
							}
						})
					}
				}
			});
			
			wx.ready(function () {
				if(title != ""){
					wx.updateAppMessageShareData({
						title: title,
						desc: desc,
						link: link,
						imgUrl: pic,
						success: function () {
						  if(callback) callback();
						}
					})
					wx.updateTimelineShareData({ 
						title: title2,
						link: link,
						imgUrl: pic
					})
				}
				if(hideOptionMenu){ //必须放在wx.read() 方法里面
					wx.hideAllNonBaseMenuItem();
				}
				if(typeof getNetworkType == 'function'){ //获取网络状态也必须放在wx.read()方法里面
					wx.getNetworkType({
						success: function (res) {
							var networkType = res.networkType; // 返回网络类型2g，3g，4g，wifi
							getNetworkType(networkType);
						}
					});
				}
			});
		}
	}
	window.ssshare = window.ssshare || ssshare;
}());