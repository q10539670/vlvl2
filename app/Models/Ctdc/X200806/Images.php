<?php


namespace App\Models\Ctdc\X200806;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'x200806_images';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\Ctdc\X200806\User');
    }
}
