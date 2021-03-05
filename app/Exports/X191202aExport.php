<?php

namespace App\Exports;

use App\Models\Sswh\X191202a\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191202aExport implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get([ 'nickname', 'prize','prize_at', 'updated_at','openid']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '奖品',
            '中奖时间',
            '参与时间',
            'openid'
        ];
    }
}
