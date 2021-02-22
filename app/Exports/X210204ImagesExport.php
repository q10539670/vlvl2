<?php

namespace App\Exports;

use App\Models\Sswh\X210204\Images;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X210204ImagesExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $images = Images::get(['id', 'poll', 'image']);
        foreach ($images as $image) {
            $image->image = 'https://cdnn.sanshanwenhua.com/statics/' . $image->image;
        }
        return $images;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '编号',
            '票数',
            '照片地址',
        ];
    }
}
