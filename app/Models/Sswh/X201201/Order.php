<?php


namespace App\Models\Sswh\X201201;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $table = 'x201201_order';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Sswh\X201201\User');
    }
}
