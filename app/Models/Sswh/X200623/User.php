<?php


namespace App\Models\Sswh\X200623;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x200623_user';

    protected $guarded = [];

    protected $firstWeek = ['2020-06-27 00:00:00','2020-07-04 23:59:59'];
    protected $secondWeek = ['2020-07-05 00:00:00','2020-07-11 23:59:59'];
    protected $thirdWeek = ['2020-07-12 00:00:00','2020-07-18 23:59:59'];
    protected $fourWeek = ['2020-07-19 00:00:00','2020-07-24 23:59:59'];

    public function getWeek()
    {
        $nowTime = date('Y-m-d H:i:s');
//        $nowTime = '2020-07-10 00:00:01';
//        $now = 0;
        if ($nowTime < $this->firstWeek[0]) {
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
        if ($nowTime > $this->fourWeek[1]) {
            $now = 5;
        }

        return $now;
    }
}
