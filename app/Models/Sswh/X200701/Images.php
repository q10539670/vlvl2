<?php


namespace App\Models\Sswh\X200701;


use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'l20200701_hn_images';

    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\Sswh\X200701\User');
    }
}
