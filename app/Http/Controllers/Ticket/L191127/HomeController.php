<?php

namespace App\Http\Controllers\Ticket\L191127;

use Illuminate\Http\Request;
use App\Models\Ticket\L191127\Admin;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('ticket.'.Admin::$itemPrefix.'.home.index');
    }


}
