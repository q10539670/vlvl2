<?php

namespace App\Exports\Qwt\Dx190925;

use App\Models\Qwt\Dx190925\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Dx190925Export implements WithMultipleSheets
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
            $sheets[] = new Dx190925Sheets($i);
        }
        return $sheets;
    }
}
