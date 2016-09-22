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
        Commands\ArchiveMigrate::class,
        Commands\TestConsole::class,
        Commands\SendMaintenenceNotification::class,
        Commands\Backup::class,
        Commands\HI8::class,
        Commands\Run::class,
        Commands\ExcelImport::class,
        Commands\Photos::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('test')
            ->everyMinute()
            ->sendOutputTo(storage_path('config/logs'));

        $schedule
            ->command('archive:notification')
            ->dailyAt('05:00')
            ->sendOutputTo(storage_path('config/logs'));

        $schedule
            ->command('archive:backup')
            ->weeklyOn(0,"01:00")
            ->sendOutputTo(storage_path('config/logs'));
    }
}
