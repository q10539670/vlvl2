<?php

namespace App\Models\Ticket\L191127;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //
    protected $guarded = [];

    public static $itemPrefix = 'l191127';

    public static $session_key = 'l191127_login';
//    protected $table = 'auto_tickets_user_v1';

//    public $timestamps = false;

    protected static function loginUser()
    {
        return [
            ['username' => 'dadada', 'passwd' => 'xiaoxiao', 'salt' => 'trgrgr'],
            ['username' => 'ttysddm', 'passwd' => 'an123kk', 'salt' => 'hhtrgrgr']
        ];
    }

    protected static function validateLogin()
    {
        $sessionKey = self::$itemPrefix.'_login';
        if (session()->has($sessionKey)) {
            $sessionToken = session()->get($sessionKey);
            foreach(self::loginUser() as $user){
                if(md5($user['username'].$user['passwd'].$user['salt']) == $sessionToken){
                    return true;
                }
            }
        }
        return false;
    }

}

//https://wx.sanshanwenhua.com/items/k_20191008/index.html