<?php

namespace App\Models\Example\Test;

use Illuminate\Database\Eloquent\Model;

class ItemLabel extends Model
{

    protected $table = 'item_label';

    protected $connection = 't5_dmsswh';

    public $timestamps = false;

    protected $guarded = [];

}
