;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function(){
/*
 Exemples :
 <a href="posts/2" data-method="delete" data-token="{{csrf_token()}}">
 - Or, request confirmation in the process -
 <a href="posts/2" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">
 */
//restful 删除
var laravel = {
    initialize: function() {
        this.methodLinks = $('a[data-method]');
        this.token = $('a[data-token]');
        this.registerEvents();
    },

    registerEvents: function() {
        this.methodLinks.on('click', this.handleMethod);
    },

    handleMethod: function(e) {
        var link = $(this);
        var httpMethod = link.data('method').toUpperCase();
        var form;

        // If the data-method attribute is not PUT or DELETE,
        // then we don't know what to do. Just ignore.
        if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
            return;
        }

        // Allow user to optionally provide data-confirm="Are you sure?"
        if ( link.data('confirm') ) {
            if ( ! laravel.verifyConfirm(link) ) {
                return false;
            }
        }

        form = laravel.createForm(link);
        form.submit();

        // e.preventDefault();
        return false;

    },

    verifyConfirm: function(link) {
        return confirm(link.data('confirm'));
    },

    createForm: function(link) {
        var form =
            $('<form>', {
                'method': 'POST',
                'action': link.attr('href')
            });

        var token =
            $('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': link.data('token')
            });

        var hiddenInput =
            $('<input>', {
                'name': '_method',
                'type': 'hidden',
                'value': link.data('method')
            });

        return form.append(token, hiddenInput)
            .appendTo('body');
    }
};

var common = {
    //设置左侧导航的选中状态
    setMenuActive : function(){
        var LocationUrl = window.location.href;
        var pathInfo = LocationUrl.substr(LocationUrl.indexOf('admin'));
        var controller = pathInfo.split('/')[1];
        var action = pathInfo.split('/')[2];
        var lis = $('#menu').find('li').not('.header');
        if(controller==undefined){
            return false;
        }
        lis.each(function(key, val){
            var urlNow = $(val).children('a:eq(0)').attr('href');
            var pathInfoNow = urlNow.substr(LocationUrl.indexOf('admin'));
            var controllerNow = pathInfoNow.split('/')[1];
            var actionNow = pathInfoNow.split('/')[2];
            if(controller == controllerNow){
                $(val).parents('ul').css('display', 'block');
                $(val).parents('li:eq(0)').addClass('menu-open');
                if(action == actionNow){
                    $(val).addClass('active');
                }
            }
        });
    },
    //隐藏提示框
    fadeOutAlert : function()
    {
        var alert = $('.alert.alert-dismissible');
        setTimeout(function(){
            alert.fadeOut(2000);
        },1500);
    }
};




    //设置左侧导航的选中状态
    // common.setMenuActive();
    //restful删除
    laravel.initialize();
    //隐藏提示框
    common.fadeOutAlert();
});