<?php

namespace App\Exports;

use App\Models\Ticket\L191127\ActivityTwo as Act2;
use App\Models\Ticket\L191127\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class L191127Act2Export implements FromCollection, WithStrictNullComparison,WithHeadings,ShouldAutoSize
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $act2 = Act2::get(['user_id', 'money', 'red_money', 'prize','name','phone','redpack_discribe','created_at','openid']);
        foreach ($act2 as $key => $user) {
            $actUser = User::where('id',$user['user_id'])->first()->toArray();
            $act2[$key]['nickname'] = $actUser['nickname'];
            $act2[$key]['avatar'] = $actUser['avatar'];
            unset($act2[$key]['user_id']);
        }
    }
    // 定义 'headings()' 方法
    public function headings(): array
    {
        return [
            '分配金额',
            '实发金额',
            '奖品',
            '姓名',
            '电话',
            '红包详情',
            '参与时间',
            'openid'
        ];
    }
}
