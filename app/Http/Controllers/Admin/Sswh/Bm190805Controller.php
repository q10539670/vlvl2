<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\Bm190726Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\bm190805\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Bm190805Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('phone','=', $nameOrPhone);
        })
        ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('truename','like','%'.$nameOrPhone.'%');
        })
        ->when($nameOrPhone == '', function($query){
            return $query->where('phone','!=', '');
        })
        ;
        $paginator = $query->paginate(10);
        $exportUrl = asset('/vlvl/sswhAdmin/excel/export');
        return view('sswh.sswhAdmin.bm190805',['title'=>'三山文化后台管理系统','paginator' => $paginator, 'exportUrl' =>$exportUrl]);
    }
    public function export()
    {
        return Excel::download(new Bm190726Export, 'users.xlsx');
    }
}
