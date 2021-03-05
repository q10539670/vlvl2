<?php

namespace App\Models\Ticket\L191127;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $guarded = [];
    protected $table = 'auto_check_v3_user';

//    public $timestamps = false;

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket\L191127\Ticket', 'user_id','id');
    }
    
}
