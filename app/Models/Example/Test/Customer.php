<?php

namespace App\Models\Example\Test;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $table = 'customer';

    protected $connection = 't5_dmsswh';

    public $timestamps = false;

    protected $guarded = [];
}
