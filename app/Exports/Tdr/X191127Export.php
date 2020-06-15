<?php

namespace App\Exports\Tdr;

use App\Models\Ticket\L191127\Ticket;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class X191127Export implements WithMultipleSheets
{
    use Exportable;

    private $where;

    public function __construct($where)
    {
        $this->where = $where;
    }



    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        // TODO: Implement sheets() method.
        $total =  Ticket::where($this->where)->count();
        $pages = ceil($total / 1000);
        $sheets = [];
        for ($i = 0; $i < $pages; $i++) {
            $sheets[] = new X191127Sheets($i,$this->where);
        }
        return $sheets;
    }

}
