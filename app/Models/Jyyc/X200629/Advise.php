<?php


namespace App\Models\Jyyc\X200629;

use Illuminate\Database\Eloquent\Model;

class Advise extends Model
{
    protected $table = 'x200629_advise';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Jyyc\X200629\User');
    }

}
