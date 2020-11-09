<?php

namespace App\Exports;

use App\Models\Sswh\X201106\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201106Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $prizeLabel = [1 => '抽奖未中奖', 2 => '中奖未确认',3=>'确认奖品'];
        $users = User::get(['nickname', 'name', 'mobile', 'prize', 'status','prized_at', 'created_at']);
        foreach ($users as $v) {
            $v['status'] = $prizeLabel[$v['status']] ?? '未抽奖';
        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '奖品',
            '状态',
            '中奖时间',
            '参与时间'
        ];
    }
}
