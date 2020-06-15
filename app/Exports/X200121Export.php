<?php

namespace App\Exports;

use App\Models\Sswh\X200121\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200121Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::get(['words', 'love','created_at','openid']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '留言',
            '热力值',
            '参与时间',
            'openid'
        ];
    }
}
