<?php


namespace App\Models\Sswh\X201201;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'x201201_user';

    protected $guarded = [];

    public function info()
    {
        return $this->hasOne('App\Models\Sswh\X201201\Info','id','info_id');
    }
}
