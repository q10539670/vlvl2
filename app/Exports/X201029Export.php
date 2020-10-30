<?php

namespace App\Exports;

use App\Models\Sswh\X201029\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201029Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get(['nickname', 'score', 'created_at','ranking_at']);
        foreach ($users as $v) {
            $v['score'] = $v['score'] / 100;
        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '成绩',
            '参与时间',
            '成绩时间',
        ];
    }
}
