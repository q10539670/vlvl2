<?php


namespace App\Models\Sswh\X201013a;

use Illuminate\Database\Eloquent\Model;


class Reserve extends Model
{
    protected $table = 'x201013a_reserve';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Sswh\X201013a\User');
    }
}
