<?php


namespace App\Models\Sswh\X201216;


use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'x201216_code';

    protected $guarded = [];

    /**
     * 生成唯一随机码
     * @param $len
     * @param $num
     * @return string
     */
    public static function getUniqueCode($len, $num)
    {
        for ($i = 0; $i < $num; $i++) {
            $randomCode = self::getRandomCode($len);
            if (!self::where('code', $randomCode)->first()) {
                return Code::create([
                    'code' => $randomCode
                ]);
            }
            return self::getUniqueCode($len);
        }

    }

    /**
     * 生成随机码
     * @param $len
     * @return string
     */
    public static function getRandomCode($len)
    {
        $str = '123456789ABCDEFHKLMNPQRSTUVWXYZabcdefhkmnpqrstuvwxyz';
        $res = '';
        $strLen = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $res .= substr($str, mt_rand(0, $strLen - 1), 1);
        }
        return $res;
    }
}
