<?php

namespace App\Exports;

use App\Models\Sswh\X200521\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200521Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get([ 'nickname', 'money','prize_at', 'updated_at','openid']);
        foreach ($users as $v) {
            $v['money'] = $v['money'] /100;
        }
        return $users;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '中奖金额(元)',
            '中奖时间',
            '参与时间',
            'openid'
        ];
    }
}
