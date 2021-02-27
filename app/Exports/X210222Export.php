<?php

namespace App\Exports;

use App\Models\Sswh\X210222\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X210222Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $statusLabel = [0=>'未抽奖', 1=>'中奖未确认',2=>'未中奖',3=>'中奖已确认'];
        $codeStatusLabel = [0=>'未核销', 1=>'已核销'];
        $users = User::get(['nickname', 'name','mobile', 'prize', 'status', 'code_status','prized_at','code_at']);
        foreach ($users as $v) {
            $v['status'] = $statusLabel[$v['status']] ?? '';
            $v['code_status'] = $codeStatusLabel[$v['code_status']] ?? '';
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
            '抽奖状态',
            '核销状态',
            '中奖时间',
            '核销时间',
        ];
    }
}
