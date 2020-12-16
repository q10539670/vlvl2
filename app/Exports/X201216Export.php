<?php

namespace App\Exports;

use App\Models\Sswh\X201216\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201216Export implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $companyLabel = [1 => '武汉生物制品研究所有限责任公司', 2 => '国药集团武汉血制公司', 3=>'武汉中生毓晋生物公司'];
        $typeLabel = [1 => '毛坯', 2 => '装修', ];
        $houseLabel = [1 => '99㎡', 2 => '108㎡',3=>'115㎡',4=>'125㎡',5=> '133㎡'];
        $payLabel = [1 => '是', 2 => '否'];
        $zigeLabel = [1 => '有', 2 => '无'];
        $users = User::where('name', '!=', '')->get([
            'nickname', 'name', 'mobile', 'company', 'zige','type', 'house', 'pay',
            'updated_at'
        ]);
        foreach ($users as $v) {
            $v['company'] = $companyLabel[$v['company']] ?? '';
            $v['type'] = $typeLabel[$v['type']] ?? '';
            $v['house'] = $houseLabel[$v['house']] ?? '';
            $v['pay'] = $payLabel[$v['pay']] ?? '';
            $v['zige'] = $zigeLabel[$v['zige']] ?? '';
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
            '所属公司',
            '是否具有购买资格',
            '意向毛坯或装修房源',
            '意向户型',
            '2020年12月25日前是否能交齐2成首付',
            '登记时间'
        ];
    }
}
