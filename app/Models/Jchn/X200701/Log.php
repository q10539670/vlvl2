<?php


namespace App\Models\Jchn\X200701;


use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'l20200701_hn_prize_log';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Jchn\X200701\User');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Jchn\X200701\Images','origin_image_id');
    }
}
