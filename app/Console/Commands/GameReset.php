<?php

namespace App\Console\Commands;

use App\Models\Sswh\X200515\User;
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
    protected $description = '每天重置游戏次数';

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
//        \DB::table('ls190726_score')->update([
//            "game_num" => 2,
//            'share_num' => 3
//        ]);

//        \DB::table('tp190822_user')->update(['vote_num' => 3]);

//        \DB::table('bt190902_user')->update([
//            'num' => 1,
//            'share_num' => 1
//        ]);

//        DB::table('tp190905_user')->update(['vote_num' => 3]); //大桥舞林争霸投票

//        DB::table('tp190911_user')->update(['vote_num' => 3]);//保利投票

//        DB::table('bl190920_user')->update([
//            'draw_num' => 3,
//            'help_num' => 0
//        ]);
//        DB::table('dx190925_user')->update([
//            'prize_num' => 2,
//            'real_share' => 0
//        ]); //电信抽奖

//        DB::table('x191022_user')->update([
//            'game_num' => 2,
//            'share_num' =>2
//        ]);  //电信抽奖
//        DB::table('x191028_user')->where('subscribe',1)->update([
//            'game_num' => 3,
//            'share_tl_num' =>1,
//            'share_f_num'=>1
//        ]);  //武汉江辰抽奖
//        DB::table('x191028_user')->where('subscribe',0)->update([
//            'game_num' => 1,
//            'share_tl_num' =>1,
//            'share_f_num'=>1
//        ]);   //武汉江辰抽奖
//
//        DB::table('x191101_user')->update([
//            'game_num' => 3,
//            'share_num' =>3
//        ]);     //跑酷

//        DB::table('x191202a_user')->where('status','!=',1)->update([
//            'prize_num' => 2,
//            'share_num' =>1
//        ]);     //奥特莱斯H5

//        DB::table('x191216_user')->update([
//            'vote_num' => 4
//        ]);
//        DB::table('x191216a_user')->update([
//            'num' => 0
//        ]);     //奥特莱斯H5
//        DB::table('x191220_user')->update([
//            'prize_num' => 3
//        ]);   //长沙龙湖天街
//        DB::table('x200102a_user')->update([
//            'prize_num' => 1,
//            'share_num' =>1
//        ]);   //华为
//        DB::table('x200103_user')->update([
//            'game_num' => 3,
//            'share_num' =>1
//        ]);   //大桥
//        DB::table('x200109_user')->update([
//            'prize_num' => 5,
//        ]);   //百事新年抽奖
//        DB::table('x200113_user')->update([
//            'num' => 3,
//        ]);   //湘中投票
//        DB::table('x200114_user')->update([
//            'game_num' => 6,
//            'share_num' =>1
//        ]);   //湘中投票
//        DB::table('x200115_user')->update([
//            'num' => 3,
//        ]);   //长沙美的投票
//        DB::table('x200424_user')->update([
//            'topic_num' => 3,
//            'status' => 0
//        ]);   //长沙美的投票
//        DB::table('x200509_user')->update([
//            'game_num' => 2,
//            'share_num' => 1,
//            'level' => 0
//        ]);   //奥莱520 天生一对
//        DB::table('x200512_user')->update([
//            'poll_num' => 1,
//            'share_num' => 3
//        ]);   //长沙美的投票
//        DB::table('x200515_user')->update([
//            'game_num' => 3,
//            'share_num' => 1
//        ]);   //长沙美的投票
//        DB::table('x200528_user')->update([
//            'game_num' => 3
//        ]);   //长沙美的投票
//        DB::table('x200612_user')->update([
//            'game_num' => 3,
//            'share_num' => 1
//        ]);   //世纪山水
//        DB::table('x200615_user')->update([
//            'prize_num' => 1,
//            'share_num' => 3
//        ]);   //世纪山水
//        DB::table('x200623_user')->update([
//            'vote_num' => 2
//        ]);   //金桥璟园
//        DB::table('x200715_user')->update([
//            'game_num' => 1,
//            'share_num' => 2
//        ]);   //世纪山水
//        DB::table('x200722_user')->update([
//            'game_num' => 1,
//            'share_num' => 1
//        ]);   //世纪山水
        DB::table('x200730_user')->update([
            'vote_num' => 3
        ]);   //宜昌中心
        return $this->info('重置成功');
    }
}
