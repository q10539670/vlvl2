<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\Lx190808Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\Lx190808\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Lx190808Controller extends Controller
{
    public function index(Request $request)
    {

        $paginator = User::where('total', '!=', 0)->where('nickname','like','%'. $request->input('name'). '%')
            ->orderBy('total', 'desc')->orderBy('updated_at', 'asc')->paginate(10);
        $exportUrl = asset('/vlvl/lx190808/excel');
        return view('sswh.sswhAdmin.lx190808',['title'=>'三山文化后台管理系统|大桥吃瓜小游戏','paginator' => $paginator, 'exportUrl' =>$exportUrl]);
    }
    public function export()
    {
        return Excel::download(new Lx190808Export, 'users.xlsx');
    }
}
