<?php

namespace App\Exports\Tdr;

use App\Models\Ticket\L191127\Ticket;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class X191127Sheets implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    use Exportable;
    private $i;

    protected $where;

    public function __construct(int $i,$where)
    {
        $this->i = $i;
        $this->where = $where;
    }

    public function collection()
    {
        // TODO: Implement collection() method.
        $checkStatus = [
            0 => '未审核',
            10 => '审核中',
            11 => '通过 【已审核】',
            12 => '不通过 【已审核】',
            13 => '审核失败',
            20 => '红包发送中',
            21 => '红包发送 【成功】',
            22 => '红包发送 【失败】',
            23 => '抽奖失败'
        ];
        return $data = Ticket::where($this->where)
            ->whereHas('user', function ($query) {
                $query->whereIn('status', [1, 2]);
            })
            ->orderBy('created_at', 'desc')
            ->offset(1000 * $this->i)
            ->limit(1000)
            ->get()
            ->map(function ($item, $key) use ($checkStatus) {
                $word_str = '';
                if (!empty($item['pic_words'])) {
                    foreach (json_decode($item['pic_words'], true) as $k => $s) {
                        if ($k > 0) $word_str .= '||';
                        $word_str .= $s;
                    }
                }
                $sendList = '';
                if (!empty($item['result_redpack'])) {
                    $sendList = json_decode($item['result_redpack'], true)['send_listid'] ?? '';
                }
                return [
                    '小票ID' => $item['id'],
                    '用户ID' => $item['user_id'],
                    '微信昵称' => '`' . $item['user']['nickname'],
                    '审核结果' => $item['result_check_msg'],
                    '审核结果详情' => $item['result_check_desc'],
                    '红包发送结果' => $item['result_redpack_desc'],
                    '微信红包单号' => $sendList,
                    '金额(元)' => '`' . ($item['money'] / 100),
                    '小票识别到的文字' => '`' . $word_str,
                    '位置' => $item['address_str'],
                    '小票上传时间' => $item['created_at'],
                    '审核时间' => $item['checked_at'],
                    '微信头像' => $item['user']['avatar'],
                    '小票地址' => env('APP_URL') . '/storage2/' . $item['img_url'],
                ];
            });
    }

    public function title(): string
    {
        // TODO: Implement title() method.
        $page = $this->i + 1;
        return '第' . $page . '页';
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            '小票ID',
            '用户ID',
            '微信昵称',
            '审核结果',
            '审核结果详情',
            '红包发送结果',
            '微信红包单号',
            '金额(元)',
            '小票识别到的文字',
            '位置',
            '小票上传时间',
            '审核时间',
            '微信头像',
            '小票地址',
        ];
    }
}
