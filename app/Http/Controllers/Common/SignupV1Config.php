<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/12
 * Time: 11:27
 */

return [
    /*
     *少儿才艺大赛报名: select  env_type as '线上环境', item_name as '项目名', truename as '姓名', phone as '电话',updated_at as '报名时间'
from sswh_signup_v1 where env_type = 1 and item_name = 'l190512a' and truename <> '';
     * */
    'l190512a' => [
        'title' => '少儿才艺大赛报名H5',   //后台标题
        'userInfo' => false, //是否userinfo 授权
        'unique' => ['phone'], //唯一验证  里面可填 openid    phone   或者为空
        'can_modify' => true,
        'front_name' => '', // 前端项目名
        'fill_key' => ['truename', 'phone', 'sex'],   // 需要填充的数据名
    ],
    /*
     *
     *  读书活动报名: select  env_type as '线上环境', item_name as '项目名', truename as '姓名', phone as '电话',age as '年龄' ,updated_at as '报名时间'
from sswh_signup_v1 where env_type = 1 and item_name = 'l190512b' and truename <> '';
     * */
    'l190512b' => [
        'title' => '读书活动报名H5',   //后台标题
        'userInfo' => false, //是否userinfo 授权
        'end_time' => '2019-07-26 23:59:59',
        'unique' => ['phone'],  //唯一验证  里面可填 openid    phone   或者为空
        'can_modify' => true,
        'front_name' => '', // 前端项目名
        'fill_key' => ['truename', 'phone', 'age'],   // 需要填充的数据名
    ],

];
