<?php

namespace App\Exports;

use App\Models\Sswh\L200430\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class L200430Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get([ 'truename', 'phone','item_name','card','num','register_at', 'openid']);
        foreach ($users as $v) {
            $v['card'] = '`'.$v['card'];
        }
        return $users;
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '姓名',
            '电话',
            '项目',
            '身份证',
            '预约人数',
            '预约时间',
            'openid'
        ];
    }
}
