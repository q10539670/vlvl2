<?php

namespace App\Exports\Qwt\X191119;

use App\Console\Commands\X191119;
use App\Models\Qwt\X191022\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class X191119Export implements WithMultipleSheets
{
    use Exportable;


    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        // TODO: Implement sheets() method.
        $total = User::count();
        $pages = ceil($total / 2000);
        $sheets = [];
        for ($i = 0; $i < $pages; $i++) {
            $sheets[] = new X191119Sheets($i);
        }
        return $sheets;
    }
}
