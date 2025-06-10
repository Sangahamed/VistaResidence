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
    protected function schedule(Schedule $schedule)
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
        $schedule->command('visits:send-reminders')->everyFiveMinutes();

          $schedule->command('model:prune', [
        '--model' => [ActivityLog::class],
        ])->daily()->when(function() {
        return config('app.env') === 'production';
        });

         // Rapports périodiques
        if (config('activity.reports.daily.enabled')) {
            $schedule->command('report:daily')
                    ->dailyAt(config('activity.reports.daily.time', '23:00'));
        }
        
        if (config('activity.reports.monthly.enabled')) {
            $schedule->command('report:monthly')
                    ->monthlyOn(
                        config('activity.reports.monthly.day', 1),
                        config('activity.reports.monthly.time', '01:00')
                    );
        }
        
        if (config('activity.reports.quarterly.enabled')) {
            $schedule->command('report:quarterly')
                    ->quarterly()
                    ->monthlyOn(
                        config('activity.reports.quarterly.day', 1),
                        config('activity.reports.quarterly.time', '02:00')
                    )
                    ->when(function () {
                        return in_array(now()->month, config('activity.reports.quarterly.months', [1, 4, 7, 10]));
                    });
        }
    }

    /**
     * Register the commands for the application.
     */

     protected $commands = [
    \App\Console\Commands\GenerateSlugs::class,
];
 
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
