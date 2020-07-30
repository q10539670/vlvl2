<?php

namespace App\Exports;

use App\Models\Jyyc\X200730\Contestant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200730Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $items = Contestant::orderBy('poll', 'desc')->orderBy('number', 'asc')->get(['number','name', 'poll']);
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
            '编号',
            '名称',
            '票数',
            '排行'
        ];
    }
}
