<?php


namespace App\Models\Sswh\X201013a;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    protected $table = 'x201013a_user';

    protected $guarded = [];

    public function reserves()
    {
        return $this->hasMany('App\Models\Sswh\X201013a\Reserve');
    }
}
