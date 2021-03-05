<?php

namespace App\Exports;

use App\Models\Sswh\X200623\Program;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200623Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Program::get([ 'number','program','poll_1','poll_2','poll_3','poll_4']);
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '编号',
            '名称',
            '第一轮成绩',
            '第二轮成绩',
            '第三轮成绩',
            '第四轮成绩',
        ];
    }
}
