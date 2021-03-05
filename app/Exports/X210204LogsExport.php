<?php

namespace App\Exports;

use App\Models\Sswh\X210204\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X210204LogsExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Log::get(['user_id','nickname', 'images_id',  'created_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '用户ID',
            '用户昵称',
            '作品ID',
            '投票时间',
        ];
    }
}
