<?php

namespace App\Exports;

use App\Models\Sswh\X191220\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191220Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get(['openid', 'nickname', 'truename', 'phone','ip','prize','prize_at','verification', 'updated_at']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '昵称',
            '真实姓名',
            '电话',
            'IP地址',
            '奖品',
            '中奖时间',
            '是否核销(0:未核销,1:已核销)',
            '参与时间'
        ];
    }
}
