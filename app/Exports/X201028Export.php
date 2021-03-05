<?php

namespace App\Exports;

use App\Models\Jyyc\X201028\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201028Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::get(['nickname', 'name', 'phone', 'score', 'help_num', 'total', 'created_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '成绩',
            '获得助力次数',
            '总成绩',
            '参与时间'
        ];
    }
}
