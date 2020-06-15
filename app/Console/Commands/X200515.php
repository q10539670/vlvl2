<?php

namespace App\Console\Commands;

use App\Models\Sswh\X200515\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class X200515 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x200515:ranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '排名';

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
        $user = User::find(1);
        $week = $user->getWeek();
        if ($week > 1) {
            $week -= 1;
        } elseif ($week == 0 && time() > strtotime(self::END_TIME)) {
            $week = 4;
        } else {
            return $this->info('未到排名时间');
        }
        $scoreName = 'score_' . $week;
        $rankingName = 'ranking_' . $week;
        if ($ranking = $user[$rankingName] == 0) {
            $where = function ($query) use ($scoreName) {
                $query->where($scoreName, '!=', 0);
            };
            $items = User::where($where)->orderBy($scoreName, 'asc')->orderBy('updated_at', 'asc')->get()->toArray();
            foreach ($items as $key => $item) {
                $userR = User::find($item['id']);
                $userR->fill([$rankingName => -1]);
                if ($key <= 2) {
                    $userR->fill([$rankingName => $key + 1]);
                }
                $userR->save();
            }
            $users = User::where($rankingName, 0)->get();
            foreach ($users as $user) {
                $user->fill([$rankingName => -1]);
                $user->save();
            }
            return $this->info('排名成功');
        } else {
            return $this->info('已排名了');
        }
    }
}
