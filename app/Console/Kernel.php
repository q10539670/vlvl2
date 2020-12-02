<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('game:reset')->dailyAt('00:01');
        $schedule->command('game:reset:week')->weeklyOn(1, '00:01');
        $schedule->command('game:reset:month')->monthlyOn(1, '00:01');
//        $schedule->command('dx190925:cmd location')->everyMinute();
//        $schedule->command('x191009:cmd location')->everyMinute();
//        $schedule->command('x191022:cmd location')->everyMinute();
//        $schedule->command('x191119:cmd location')->everyMinute();
//        $schedule->command('x191125:cmd location')->everyMinute();
//        $schedule->command('ticket_l191127:cmd check')->everyMinute();
//        $schedule->command('ticket_l191127:cmd sendredpack')->everyMinute();
//        $schedule->command('ticket_l191127:cmd recheckFail')->hourly();
//        $schedule->command('x191220:cmd location')->everyMinute(); //长沙天街 娃娃机抽奖
//        $schedule->command('dx190925:cmd export')->everyMinute();
        $schedule->command('x201201:sendMsg')->dailyAt('17:00');
        $schedule->command('x201201a:sendMsg')->dailyAt('18:00');
//        $schedule->command('x200701:sendMsg')->everyMinute();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
