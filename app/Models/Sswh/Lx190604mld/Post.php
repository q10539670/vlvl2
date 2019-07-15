<?php


namespace App\Models\Sswh\Lx190604mld;


use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'lx190604mld_post';

    protected $guarded = [];

//    public $timestamps = false;



    /**
    9      * 获取评论对应的博客文章
    10      */
     public function userInfo()
     {
          return $this->belongsTo('App\Models\Sswh\Lx190604mld\User', 'user_id', 'id');
    }


}