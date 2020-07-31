<?php


namespace App\Models\Jchn\X200701;


use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'l20200701_hn_images';

    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\Jchn\X200701\User');
    }

    public function admin() {
        return $this->belongsTo('App\Models\Jchn\X200701\Admin');
    }

}