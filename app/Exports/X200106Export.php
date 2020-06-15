<?php

namespace App\Exports;

use App\Models\Jyyc\X200106\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200106Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get([ 'nickname','prize', 'round','prized_at', 'updated_at','openid']);
        foreach ($users as $v) {
            $v['round'] = '第' . $v['round'] . '轮';
        }
        return $users;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '中奖奖品',
            '中奖轮数',
            '中奖时间',
            '参与时间',
            'openid'
        ];
    }
}

