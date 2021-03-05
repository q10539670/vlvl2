<?php

namespace App\Exports;

use App\Models\Sswh\bm190805\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Bm190726Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('truename', '!=', '')->get(['openid', 'nickname', 'truename', 'phone', 'sex', 'age', 'cooking_age', 'specialty', 'updated_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '昵称',
            '姓名',
            '电话',
            '性别(0:男;1:女)',
            '年龄',
            '厨龄',
            '拿手菜',
            '报名时间'
        ];
    }
}
