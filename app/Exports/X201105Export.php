<?php

namespace App\Exports;

use App\Models\Sswh\X201105\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201105Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::get([ 'nickname', 'score','updated_at','openid']);
        return $users;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '分数',
            '参与时间',
            'openid'
        ];
    }
}
