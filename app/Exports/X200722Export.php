<?php

namespace App\Exports;

use App\Models\Sswh\X200722\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200722Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $items = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->get(['nickname', 'score','created_at']);
        foreach ($items as $key => $item) {
            $items[$key]['ranking'] = $key + 1;
//            array_push($item, ['ranking' => $key + 1]);
        }

        return $items;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '成绩',
            '参与时间',
            '排行'
        ];
    }
}
