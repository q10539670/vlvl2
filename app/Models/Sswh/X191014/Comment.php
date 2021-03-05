<?php


namespace App\Models\Sswh\X191014;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'x191014_comment';

    protected $guarded = [];

    //关联用户表
    public function user()
    {
        return $this->belongsTo('App\Models\Sswh\X191014\User', 'user_id', 'id')->withDefault();
    }

    //关联商家表
    public function shop()
    {
        return $this->belongsTo('App\Models\Sswh\X191014\Shop', 'shop_id', 'id')->withDefault();
    }
}
