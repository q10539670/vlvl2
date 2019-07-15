<?php

namespace App\Models\Example\Test;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $table = 'item';

    protected $connection = 't5_dmsswh';

    public $timestamps = false;

    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne('\App\Models\Example\Test\Customer','id','customer_id');
    }

    public function labels()
    {
        return $this->belongsToMany('\App\Models\Example\Test\ItemLabel',
            'item_label_relation','item_id','label_id');
    }
}
