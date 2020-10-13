<?php

namespace App\Exports;

use App\Models\Sswh\X201013b\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201013bExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $genderLabel = [1 => '男', 2 => '女'];
        $onFootLabel = [1 => '有', 2 => '无'];
        $users = User::get(['nickname', 'name', 'mobile', 'gender', 'on_foot','date','comment_1','comment_2', 'created_at']);
        foreach ($users as $v) {
            $v['gender'] = $genderLabel[$v['gender']] ?? '未知性别';
            $v['on_foot'] = $onFootLabel[$v['on_foot']] ?? '经验未知';
        }
        return $users;
    }

    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '昵称',
            '姓名',
            '电话',
            '性别',
            '徒步经验',
            '预约时间',
            '身体状况自检(17)',
            '身体状况自检(24)',
            '参与时间'
        ];
    }
}
