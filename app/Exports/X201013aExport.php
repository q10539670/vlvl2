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
        $reserves = Reserve::get(['name', 'phone', 'num', 'reserve_date', 'reserve_time', 'user_id']);
        foreach ($reserves as $reserve) {
            $reserve['nickname'] = $reserve->user->nickname;
            $reserve['reserve_time'] = $reserve['reserve_time'].'点';
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
