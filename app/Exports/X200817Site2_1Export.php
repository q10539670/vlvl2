<?php

namespace App\Exports;

use App\Models\Sswh\X200817\Site2_1User as User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200817Site2_1Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::get(['nickname','name','phone','room_no','round','prize','created_at','prized_at']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '房号',
            '第几轮中奖',
            '奖品',
            '参与时间',
            '中奖时间'
        ];
    }
}
