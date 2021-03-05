<?php

namespace App\Exports;

use App\Models\Sswh\X210203\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X210203Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::get(['nickname','name','mobile','num','item','room_no','sign_up_at','created_at']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '参与人数',
            '项目',
            '房号',
            '报名时间',
            '参与时间'
        ];
    }
}
