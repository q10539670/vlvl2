<?php

namespace App\Exports;

use App\Models\Sswh\X200413\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200413Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::get(['name','phone','status','price_1','help_num_1','price_2','help_num_2','created_at','openid']);
        return $user;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '姓名',
            '电话',
            '状态',
            '商品1砍价',
            '商品1帮助人数',
            '商品2砍价',
            '商品2帮助人数',
            '参与时间',
            'openid'
        ];
    }
}
