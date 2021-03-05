<?php

namespace App\Exports;

use App\Models\Sswh\X190929\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X190929Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get(['openid', 'nickname', 'truename', 'phone','prize','prize_at', 'updated_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '昵称',
            '真实姓名',
            '电话',
            '奖品',
            '中奖时间',
            '参与时间'
        ];
    }
}
