<?php

namespace App\Exports;

use App\Models\Sswh\X201224\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201224Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $genderLabel = [1 => '男', 2 => '女'];
        $typeLabel = [1 => '祝“福”志愿者', 2 => '护航志愿者'];
        $users = User::where('name', '!=', '')->get([
            'nickname', 'name', 'gender', 'age', 'mobile', 'address', 'id_num', 'type', 'comment', 'updated_at'
        ]);
        foreach ($users as $v) {
            $v['gender'] = $genderLabel[$v['gender']] ?? '';
            $v['id_num'] = '`' . $v['id_num'];
            $v['type'] = $typeLabel[$v['type']] ?? '';
        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '性别',
            '年龄',
            '电话',
            '地址',
            '身份证',
            '志愿者类型',
            '爱心宣言',
            '报名时间'
        ];
    }
}
