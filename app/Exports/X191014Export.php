<?php

namespace App\Exports;

use App\Models\Sswh\X191014\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191014Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get(['openid', 'nickname','prize','prize_at','verification', 'created_at']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '昵称',
            '奖品',
            '中奖时间',
            '是否核销(0:未核销,1:已核销)',
            '参与时间'
        ];
    }
}
