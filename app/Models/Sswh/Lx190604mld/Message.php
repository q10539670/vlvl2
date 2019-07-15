<?php


namespace App\Models\Sswh\Lx190604mld;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'lx190604mld_message';

    protected $guarded = [];

//    public $timestamps = false;



    /**
     * 获取提交人的信息
     */
    public function userInfo()
    {
        return $this->belongsTo('App\Models\Sswh\Lx190604mld\User', 'user_id', 'id');
    }

}