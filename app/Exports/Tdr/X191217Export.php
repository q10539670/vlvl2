<?php


namespace App\Exports\Tdr;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class X191217Export implements FromCollection, WithHeadings
{

    public $data;


    public function __construct(array $data)
    {
        $this->data     = $data;

    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // TODO: Implement collection() method.
        return collect($this->data);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            '省',
            '市',
            '地区',
            '参与人数',
            '上传小票次数',
            '审核通过小票',
            '发红包数',
            '发红包金额'
        ];
    }

}
