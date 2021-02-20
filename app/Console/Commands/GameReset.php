<?php

namespace App\Console\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class GameReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天重置次数';

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
     * @throws GuzzleException
     */
    public function handle()
    {
        DB::table('x210126_user')->update([
            'num' => 3
        ]); //天麓城投票 停止时间:2021-02-26 23:59:59
//        DB::table('x210204_user')->update([
//            'num' => 10
//        ]); //天麓城投票 停止时间:2021-02-18 23:59:59
        return $this->info('重置成功');
    }
}
