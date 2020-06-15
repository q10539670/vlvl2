<?php

namespace App\Exports;

use App\Models\Sswh\X191008\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X191008Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::get(['openid', 'nickname', 'truename', 'phone','id_num','projects','prize', 'updated_at']);
        foreach ($users as $v) {
//            $v['phone'] = '`'.$v['phone'];
            $v['id_num'] = '`'.$v['id_num'];
        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            'openid',
            '昵称',
            '真实姓名',
            '电话',
            '身份证号',
            '项目名称',
            '奖品',
            '参与时间'
        ];
    }
}
