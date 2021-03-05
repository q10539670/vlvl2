<?php

namespace App\Exports;

use App\Models\Sswh\X200305\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X20305Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::get(['name','phone','likes','created_at','openid']);
        return $user;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '姓名',
            '电话',
            '点赞数',
            '参与时间',
            'openid'
        ];
    }
}
