<?php

namespace App\Exports;

use App\Models\Sswh\X200515\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X200515Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::get([ 'nickname','name','phone','address','map','score', 'updated_at','openid']);
        foreach ($users as $v) {
            $v['score'] /= 100;
            if ($v['map'] == 1) {
                $v['map'] = '故宫';
            }
            if ($v['map'] == 2) {
                $v['map'] = '黄鹤楼';
            }
            if ($v['map'] == 3) {
                $v['map'] = '埃菲尔铁塔';
            }
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
            '地址',
            '地图',
            '用时',
            '游戏记录时间',
            'openid'
        ];
    }
}
