<?php

namespace App\Exports;

use App\Models\Jyyc\X200629\Advise;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200629Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $advises = Advise::get([ 'user_id','advise','name','phone','created_at']);
        foreach ($advises as $advise) {
            $advise['user_id'] = $advise->user->nickname;
        }
        return $advises;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '建议',
            '姓名',
            '电话',
            '留言时间'
        ];
    }
}
