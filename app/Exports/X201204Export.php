<?php

namespace App\Exports;

use App\Models\Sswh\X201204\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201204Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $genderLabel = [1 => '男', 2 => '女'];
        $dishesLabel = [1 => '咖喱蟹', 2 => '避风塘蟹', 3 => '卤蟹', 4 => '香辣蟹', 5 => '蒜香螃蟹', 6 => '花雕熟醉蟹', 7 => '其它'];
        $users = User::where('name', '!=', '')->get([
            'nickname', 'name', 'age', 'id_num', 'mobile', 'gender', 'dishes', 'main', 'accessories', 'flavoring',
            'updated_at'
        ]);
        foreach ($users as $v) {
            $v['gender'] = $genderLabel[$v['gender']] ?? '';
            $v['id_num'] = '`'.$v['id_num'];
            $v['dishes'] = $dishesLabel[$v['dishes']] ?? '';
            $v['main'] = $v['main'] ?'螃蟹'.$v['main'].'只' : '';
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
            '菜品',
            '主料',
            '辅料',
            '调味品',
            '报名时间'
        ];
    }
}
