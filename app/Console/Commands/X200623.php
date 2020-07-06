<?php

namespace App\Console\Commands;

use App\Models\Sswh\X200623\Program;
use App\Models\Sswh\X200623\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class X200623 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x200623:ranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '排名';

    const END_TIME = '2020-07-24 23:59:59';

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
        $user = new User();
        $week = $user->getWeek();
        if ($week > 1) {
            $week -= 1;
        } elseif ($week == 0 && time() > strtotime(self::END_TIME)) {
            $week = 4;
        } else {
            return $this->info('未到排名时间');
        }
        $ranking = $week * 3;
        $pollName = 'poll_' . $week;

        if (!Program::where('ranking', $ranking)->first()) {
            $where = function ($query) use ($pollName) {
                $query->where($pollName, '!=', 0)->where('ranking','<',1);
            };
            $items = Program::where($where)->orderBy($pollName, 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
            foreach ($items as $key => $item) {
                $userR = Program::find($item['id']);
                if ($key <= 2) {
                    $userR->fill(['ranking' => $key + (($week - 1) * 3) + 1]);
                }
                $userR->save();
            }
            return $this->info('排名成功');
        } else {
            return $this->info('已排名了');
        }
    }
}
