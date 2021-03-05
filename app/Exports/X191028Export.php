<?php

namespace App\Exports;

use App\Models\Jctj\X191028\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191028Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get(['nickname', 'truename', 'phone', 'address', 'prize', 'created_at', 'openid']);
//        foreach ($users as $v) {
////            $v['phone'] = '`'.$v['phone'];
//            $v['id_num'] = '`'.$v['id_num'];
//        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '真实姓名',
            '电话',
            '地址',
            '奖品',
            '参与时间',
            'openid'
        ];
    }
}
