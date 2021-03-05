<?php

namespace App\Exports;

use App\Models\Jyyc\X191211\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191211Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get([ 'nickname', 'name', 'phone','money','prized_at', 'updated_at','openid']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '中奖金额(分)',
            '中奖时间',
            '参与时间',
            'openid'
        ];
    }
}
