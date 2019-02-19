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
        \App\Console\Commands\SendBhzWarn::class,
        \App\Console\Commands\Stat::class,
        \App\Console\Commands\SendSdtj::class,
        \App\Console\Commands\Resource::class,
        \App\Console\Commands\WarnMess::class,
        \App\Console\Commands\Monitor::class,
        \App\Console\Commands\WeatherStatPush::class,
        \App\Console\Commands\AcquireWeather::class,
        \App\Console\Commands\MixplantDeviceIsAbnormalJudge::class,
    ];

    /**
     * Define the application's command schedule.  ->dailyAt('15:53')
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stat')
                 ->dailyAt('0:30');
        $schedule->command('sendsdtj')
                 ->everyFiveMinutes();
        $schedule->command('resource')
                 ->everyThirtyMinutes();
        $schedule->command('warnmess')
            ->dailyAt('8:10');
        $schedule->command('monitor')
                 ->everyTenMinutes();
        $schedule->command('acquire_weather')
            ->dailyAt('0:05');

    }
}
