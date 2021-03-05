<?php

namespace App\Exports;

use App\Models\Sswh\X201119\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201119Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::where('mobile','!=','')->get(['nickname', 'name', 'mobile', 'room', 'num','updated_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '房号',
            '参与人数',
            '报名时间'
        ];
    }
}
