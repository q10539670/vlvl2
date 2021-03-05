<?php

namespace App\Exports;

use App\Models\Ctdc\X210111\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X210111Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $statusLabel = [0=>'未抽奖', 1=>'已中奖',2=>'未中奖'];
        $users = User::where('status',1)->get(['nickname', 'name','mobile', 'prize', 'status', 'prized_at']);
        foreach ($users as $v) {
            $v['status'] = $statusLabel[$v['status']] ?? '';
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
            '中奖时间'
        ];
    }
}
