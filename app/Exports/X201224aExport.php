<?php

namespace App\Exports;

use App\Models\Sswh\X201224a\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class X201224aExport implements FromCollection, WithStrictNullComparison, WithHeadings, ShouldAutoSize
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $xmLabel = [1=>'方岛金茂智慧科学城',2=> '金茂华发武汉国际社区',3=> '滨江金茂府',4=> '东湖金茂府',5=>'华发阳逻金茂逸墅',6=> '阳逻金茂逸墅',7=> '阳逻金茂悦'];
        $users =  User::get(['name', 'mobile','xm','fh', 'comment', 'updated_at']);
        foreach ($users as $v) {
            $v['xm'] = $xmLabel[$v['xm']] ?? '';
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
            '房号',
            '留言',
            '留言时间'
        ];
    }
}
