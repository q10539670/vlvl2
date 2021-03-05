<?php

namespace App\Exports\Qwt\X191022;

use App\Models\Qwt\X191022\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class X191022Sheets implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    use Exportable;
    private $i;

    public function __construct(int $i)
    {
        $this->i = $i;
    }

    public function collection()
    {
        // TODO: Implement collection() method.
        $prizeStatus = [0 => '未抽奖', 1 => '中奖', 2 => '未中奖【红包发送失败】', 3=>'未中奖【未抽中奖】'];
        $subStatus = [0 => '未关注', 1 => '关注'];
        return $dataArr = User::orderBy('created_at', 'asc')
            ->offset(2000 * $this->i)
            ->limit(2000)
            ->get()
            ->map(function ($item, $key) use ($subStatus, $prizeStatus) {
                return [
                    'openid' => $item['openid'],
                    '昵称' => '`' . $item['nickname'],
                    '是否关注' => $subStatus[$item['subscribe']],
                    '用户选择归属地' => $item['virtual_address_str'],
                    '手机定位地址' => $item['address_str'],
                    '抽奖详情' => $prizeStatus[$item['status']],
                    '首次进入时间' => $item['created_at'],
                    '抽奖时间' => $item['prize_at'],
                    '头像' => $item['avatar'],
                ];
            });

    }

    public function title(): string
    {
        // TODO: Implement title() method.
        $page = $this->i +1;
        return '第' . $page . '页';
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'openid',
            '昵称',
            '是否关注',
            '用户选择归属地',
            '手机定位地址',
            '抽奖详情',
            '首次进入时间',
            '抽奖时间',
            '头像',
        ];
    }
}
