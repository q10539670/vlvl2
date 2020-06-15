<?php


namespace App\Models\Sswh\X191219;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x191219_user';

    protected $guarded = [];

    /*
     *
     * 随机获取$ids数组之外的用户
     */
    public function getUser($ids)
    {
        $prizeNum = User::where('make', 1)->where('status',0)->whereNotIn('id', $ids)->count();
        $random = mt_rand(0, $prizeNum - 1);
        $user = User::where('make', 1)->where('status',0)->whereNotIn('id', $ids)->offset($random)->limit(1)->get()->toArray();
        return $user[0];
    }

    /*
 * 查询内定信息
 */
    public function getPrizeId($id_nums)
    {
        if ($user = User::whereIn('id_num', $id_nums)->get('id')->toArray()) {
            return  array_column($user,'id');
        }
        return [];
    }

    /*
 * 查询内定信息
 */
    public function getPrize($id_nums)
    {
        if ($this->getPrizeId($id_nums)){
            return User::whereIn('id',$this->getPrizeId($id_nums))->get()->toArray();
        }
        return [];
    }
}
