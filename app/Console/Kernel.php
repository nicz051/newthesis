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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('command:senddisconnection')
        //          ->everyMinute();
                
        // $schedule->command('command:sendreminder')
        //          ->everyMinute();

        // $schedule->command('command:message')
        // ->everyMinute();

        $schedule->command('command:checkincomingmessages')
                 ->everyMinute();
        
        $schedule->command('command:sendreconnection')
                ->everyMinute();

        //  $schedule->command('command:clearreconnected')
        //          ->everyTenMinutes();
      
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
