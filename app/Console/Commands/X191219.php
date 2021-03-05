<?php

namespace App\Console\Commands;

use App\Models\Sswh\X191219\Info;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Sswh\X191219\User;

class X191219 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x191219:cmd {handle?}';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $handle = $this->argument('handle');
        switch ($handle) {
            case 'auth':    //解析定位 (每分钟运行一次)
                $this->parseAuth();
                break;
            default:
                echo '默认';
        }
    }

    /*
     * 验证业主信息
     *
     * */
    protected function parseAuth()
    {
        $users = User::where('make',0)->get();
        foreacH ($users as $user) {
            if ($info = Info::where(['name'=>$user->name,
                'id_num'=>$user->id_num,
                'phone'=>$user->phone,
                'status'=>0])->first()){
                $user->make = 1;
                $info->status = 1;
                $info->save();
            } else {
                $user->make = 0;
            }
            $user->save();
        }
    }

}
