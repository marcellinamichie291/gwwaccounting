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
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('migrate:customer')->everyFiveMinutes();
        $schedule->command('migrate:vehicle')->everyFiveMinutes();

        // Not installed yet
        if (!config('app.installed')) {
            return;
        }

        $schedule_time = config('app.schedule_time');

        $schedule->command('reminder:invoice')->dailyAt($schedule_time);
        $schedule->command('reminder:bill')->dailyAt($schedule_time);
//        $schedule->command('recurring:check')->dailyAt($schedule_time)->runInBackground();
        $schedule->command('storage-temp:clear')->dailyAt('17:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');

        $this->load(__DIR__ . '/Commands');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return config('app.timezone');
    }
}
