<?php

namespace App\Models\Tdr\Wuhan;

use Illuminate\Database\Eloquent\Model;

class TdrUsers extends Model
{
    protected $table = 'user_tdr';

    protected $guarded = [];

    public $timestamps = false;
}
