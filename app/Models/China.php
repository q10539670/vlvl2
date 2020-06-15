<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class China extends Model
{
    //

    protected $table = 'china_im';

    protected $guarded = [];

    public $timestamps = false;

    /*找出父元素名称*/
    public static function getAllName($areaId = 420102)
    {
        $res = [];
        $ins = self::where('id',$areaId)->first();
        $res[] = $ins->name;
        if($ins->pid !=0){
            $res= array_merge(self::getAllName($ins->pid),$res);
        }
        return $res;
    }

    /*
     * 找出指定元素的所有子元素
     * */
    public function getAllChildArea($areaId)
    {
        $res = [];
        $res[] = $areaId;
        if($ins = self::where('pid',$areaId)->get()){
            foreach ($ins as $in){
                $res = array_merge($this->getAllChildArea($in->id),$res);
            }
        }
        return $res;
    }

}
