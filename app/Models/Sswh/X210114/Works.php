<?php


namespace App\Models\Sswh\X210114;


use Illuminate\Database\Eloquent\Model;

class Works extends Model
{
    protected $table = 'x210114_works';

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo('App\Models\Sswh\X210114\User');
    }
}
