<?php

namespace App\Exports;

use App\Models\Jyyc\X200928\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200928Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $statusLabel = [0 => '未抽奖', 10 => '中奖未兑奖', 11 => '中奖已兑奖', 12 => '中奖已过期', 2 => '未中奖', 3 => '红包发送失败'];
        $users = User::get(['nickname', 'name', 'phone', 'money', 'status', 'prized_at', 'created_at']);
        foreach ($users as $v) {
            $v['money'] = $v['money'] / 100;
            $v['status'] = $statusLabel[$v['status']] ?? '未识别状态';
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
            '中奖金额',
            '状态',
            '中奖时间',
            '参与时间'
        ];
    }
}
