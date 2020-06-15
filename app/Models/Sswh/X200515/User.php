<?php


namespace App\Models\Sswh\X200515;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x200515_user';

    protected $guarded = [];


    protected $firstWeek = ['2020-06-01 00:00:00','2020-06-07 23:59:59'];
    protected $secondWeek = ['2020-06-08 00:00:00','2020-06-14 23:59:59'];
    protected $thirdWeek = ['2020-06-15 00:00:00','2020-06-21 23:59:59'];
    protected $fourWeek = ['2020-06-22 00:00:00','2020-06-28 23:59:59'];

    public function getWeek()
    {
        $nowTime = date('Y-m-d H:i:s');
//        $nowTime = '2020-06-02 00:00:01';
        $now = 0;
        if ($nowTime < $this->firstWeek[0] && $nowTime > $this->fourWeek[1]) {
            $now = 0;
        }
        if ($nowTime >= $this->firstWeek[0] && $nowTime <= $this->firstWeek[1]) {
            $now = 1;
        }
        if ($nowTime >= $this->secondWeek[0] && $nowTime <= $this->secondWeek[1]) {
            $now = 2;
        }
        if ($nowTime >= $this->thirdWeek[0] && $nowTime <= $this->thirdWeek[1]) {
            $now = 3;
        }
        if ($nowTime >= $this->fourWeek[0] && $nowTime <= $this->fourWeek[1]) {
            $now = 4;
        }
        return $now;
    }
}
