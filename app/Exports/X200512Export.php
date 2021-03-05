<?php

namespace App\Exports;

use App\Models\Sswh\X200512\Poll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200512Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Poll::get(['number','name','polls','created_at']);
        return $user;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '编号',
            '名字',
            '票数',
            '参与时间'
        ];
    }
}
