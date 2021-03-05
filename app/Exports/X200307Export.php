<?php

namespace App\Exports;

use App\Models\Sswh\X200307\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200307Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::get(['name','phone','created_at','openid']);
        return $user;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '姓名',
            '电话',
            '报名时间',
            'openid'
        ];
    }
}
