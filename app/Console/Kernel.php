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

        Commands\SyncITrack::class,
        Commands\Notify::class,
        Commands\Statuses::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('SyncITrack')->appendOutputTo(env('PROJECT_DIR') . "/storage/logs/sync.log")
            ->cron('59 23 * * *');
        $schedule->command('Notify')->appendOutputTo(env('PROJECT_DIR') . "/storage/logs/notify.log")
            ->cron('*/5 * * * *');
        $schedule->command('Statuses')->appendOutputTo(env('PROJECT_DIR') . "/storage/logs/statuses.log")
            ->cron('*/5 * * * *');

    }
}
