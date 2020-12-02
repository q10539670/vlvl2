<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class GameResetForMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:reset:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月重置次数';

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
        DB::table('x201201_user')->update([
            'm_num' => 0
        ]);   //三山点餐
        return $this->info('重置成功');
    }
}
