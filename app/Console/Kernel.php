<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        'App\Console\Commands\LeaveCommand',
        'App\Console\Commands\UpdateLeaveCommand',
        'App\Console\Commands\InactiveLeaveCommand',
    ];
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('leave:create-date')
            ->everyMinute();
        $schedule->command('user:resignation-date')
            ->everyMinute();
        $schedule->command('leave:update-leave')
            // ->monthlyOn(1, '00:00');
            ->everyMinute();
        $schedule->command('command:dailyTimesheet')->daily();
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
