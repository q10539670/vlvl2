<?php


namespace App\Models\Sswh\X201013;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $table = 'x201013_title';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Sswh\X201013\User');
    }
}
