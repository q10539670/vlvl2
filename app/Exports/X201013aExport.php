<?php

namespace App\Exports;

use App\Models\Sswh\X201013a\Reserve;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201013aExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $timeInfo = [
            ["id"=>11,"time"=>"11:30-12:30"],
            ["id"=>12,"time"=>"12:30-13:30"],
            ["id"=>13,"time"=>"13:30-14:30"],
            ["id"=>14,"time"=>"14:30-15:30"],
            ["id"=>15,"time"=>"15:30-16:30"],
            ["id"=>16,"time"=>"16:30-17:30"],
            ["id"=>17,"time"=>"17:30-18:30"],
            ["id"=>18,"time"=>"18:30-19:30"],
            ["id"=>19,"time"=>"19:30-20:00"],
        ];
        $reserves = Reserve::get(['name', 'phone', 'num', 'reserve_date', 'reserve_time', 'user_id']);
        foreach ($reserves as $reserve) {
            $reserve['nickname'] = $reserve->user->nickname;
            foreach($timeInfo as $v){
                if ($reserve['reserve_time']==$v['id']){
                    $reserve['reserve_time']=$v['time'];
                }
            }
//            $reserve['reserve_time'] = $reserve['reserve_time'].'点';
            unset($reserve['user_id']);
        }
        return $reserves;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '姓名',
            '电话',
            '预约人数',
            '预约日期',
            '预约时间',
            '昵称',
        ];
    }
}
