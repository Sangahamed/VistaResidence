<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\BackupDatabase;
use App\Console\Commands\CleanupTempFiles;
use App\Console\Commands\GenerateMonthlyReports;
use App\Console\Commands\GenerateWeeklyReports;
use App\Console\Commands\SendPropertyNewsletters;
use App\Console\Commands\SendSearchAlerts;
use App\Console\Commands\SendVisitReminders;
use App\Console\Commands\SyncExternalServices;
use App\Console\Commands\TrackInactiveLeads;
use App\Console\Commands\UpdatePropertyStatuses;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('search:send-alerts instant')->everyFifteenMinutes();
        $schedule->command('search:send-alerts daily')->dailyAt('09:00');
        $schedule->command('search:send-alerts weekly')->weeklyOn(1, '09:00');
        $schedule->command('search:send-alerts monthly')->monthlyOn(1, '09:00');

        $schedule->command('app:send-visit-reminders')->dailyAt('08:00');
        $schedule->command('app:update-property-statuses')->dailyAt('00:00');
        $schedule->command('app:generate-weekly-reports')->weeklyOn(1, '07:00');
        $schedule->command('app:generate-monthly-reports')->monthlyOn(1, '06:00');
        $schedule->command('app:track-inactive-leads')->dailyAt('09:00');
        $schedule->command('app:send-property-newsletters')->weeklyOn(5, '10:00');
        $schedule->command('app:cleanup-temp-files')->weeklyOn(0, '02:00');
        $schedule->command('app:backup-database')->dailyAt('01:00')->environments(['production']);
        $schedule->command('app:sync-external-services')->everyFourHours();
        $schedule->command('reminders:send')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
