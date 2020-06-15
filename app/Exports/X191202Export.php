<?php

namespace App\Exports;

use App\Models\Jyyc\X191202\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191202Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get(['nickname', 'name', 'phone','auth','prize','prize_code','prized_at', 'updated_at','openid']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '是否为业主(0:不是,1:是)',
            '奖品',
            '核销码',
            '中奖时间',
            '参与时间',
            'openid'
        ];
    }
}
