<?php

namespace App\Exports;

use App\Models\Sswh\X200212\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200212Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::get(['score','created_at','updated_at','openid']);
        foreach ($user as $v) {
            $v['score'] = $v['score'] / 100;
        }
        return $user;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '成绩',
            '参与时间',
            '游戏记录时间',
            'openid'
        ];
    }
}
