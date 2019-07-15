<?php

namespace App\Http\Controllers\Example;

use App\Models\Example\Test\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

class TestController extends Controller
{
    //
    public function test()
    {
        return $this->returnJson(1,"查询成功");
    }

    public function testWeb()
    {
//        for($i=0;$i<10000;$i++){
//            Item::create([
//                'title'=>'标题',
//                'customer_id'=>$i,
//            ]);
//        }
        $item = Item::with('customer','labels')->paginate();
////        dd($item);
      return   5555;
//        return view('welcome');
    }
}
