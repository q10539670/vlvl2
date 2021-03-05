<?php

namespace App\Exports;

use App\Models\Sswh\X191029\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191029Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get(['nickname', 'name', 'phone', 'room_num', 'id_num', 'banquet','created_at']);
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
            '姓名',
            '电话',
            '房号',
            '身份证号码',
            '私宴名称',
            '参与时间'
        ];
    }
}
