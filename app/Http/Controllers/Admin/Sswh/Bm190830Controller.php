<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\Bm190830Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\Bm190830\Team;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Bm190830Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $query = Team::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('phone','=', $nameOrPhone);
        })
        ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('team_name','like','%'.$nameOrPhone.'%');
        })
        ->where('phone','!=', '');
        ;
        $paginator = $query->paginate(10);
        $exportUrl = asset('/vlvl/bm190830/export');
        return view('sswh.sswhAdmin.bm190830',['title'=>'三山文化后台管理系统','paginator' => $paginator, 'exportUrl' =>$exportUrl]);
    }
    public function export()
    {
        return Excel::download(new Bm190830Export, 'teams.xlsx');
    }
}
