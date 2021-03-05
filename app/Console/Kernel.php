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
        $schedule->command('getApiData')->dailyAt('07:00');
        $schedule->command('game:reset:week')->weeklyOn(1, '00:01');
        $schedule->command('game:reset:month')->monthlyOn(1, '00:01');
//        $schedule->command('ticket_l191127:cmd sendredpack')->everyMinute();
//        $schedule->command('ticket_l191127:cmd recheckFail')->hourly();
        $schedule->command('x201201:sendMsg')->dailyAt('17:00');
        $schedule->command('x201201a:sendMsg')->dailyAt('18:00');
        $schedule->command('sendWeatherEveryDay')->dailyAt('07:30');
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
