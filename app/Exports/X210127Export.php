<?php

namespace App\Exports;

use App\Models\Sswh\X210127\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X210127Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::get(['nickname','prize', 'score','num','prized_at', 'created_at', 'openid']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '奖品',
            '游戏分数',
            '游戏次数',
            '中奖时间',
            '参与时间',
            'openid'
        ];
    }
}
