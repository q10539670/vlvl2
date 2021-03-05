<?php

namespace App\Exports;

use App\Models\Sswh\Lx190808\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Lx190808Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('total', '!=', 0)->orderBy('total', 'desc')->orderBy('updated_at', 'asc')->get(['openid', 'nickname', 'avatar', 'total', 'updated_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '昵称',
            '头像',
            '总分',
            '最后游戏时间'
        ];
    }
}
