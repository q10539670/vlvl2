<?php

namespace App\Exports;

use App\Models\Sswh\X201109\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201109Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $genderLabel = [1 => '男', 2 => '女'];
        $users = User::get(['nickname', 'name', 'age','id_num','mobile', 'gender', 'comment','reason', 'updated_at']);
        foreach ($users as $v) {
            $v['gender'] = $genderLabel[$v['gender']] ?? '';
            $v['id_num'] = '`'.$v['id_num'];
        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '年龄',
            '身份证',
            '电话',
            '性别',
            '心目中的老味道',
            '加入吃货团的理由',
            '报名时间'
        ];
    }
}
