<?php

namespace App\Exports;

use App\Models\Sswh\X201208\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201208Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::where('image', '!=','')->get(['nickname', 'name', 'mobile', 'slogan', 'polls', 'created_at', 'openid']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '宣言',
            '票数',
            '参与时间',
            'openid'
        ];
    }
}
