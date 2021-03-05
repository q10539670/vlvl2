<?php

namespace App\Exports;

use App\Models\Sswh\Bm190830\Team;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Bm190830Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Team::where('team_name', '!=', '')->get(['openid', 'avatar', 'team_name', 'team_peoples', 'team_introduction', 'team_age', 'phone', 'updated_at']);
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '头像',
            '舞团名称',
            '舞团人数',
            '舞团简介',
            '成员均龄',
            '负责人电话',
            '报名时间'
        ];
    }
}
