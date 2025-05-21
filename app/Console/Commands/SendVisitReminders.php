<?php

namespace App\Console\Commands;

use App\Models\PropertyVisit;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendVisitReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send visit reminders to participants';

    public function handle()
    {
        $service = app(NotificationService::class);
        
        // 24h reminders
        $visits24h = PropertyVisit::whereBetween('visit_date', [
            now()->addHours(23),
            now()->addHours(25)
        ])->get();
        
        foreach ($visits24h as $visit) {
            if ($visit->status === 'confirmed') {
                $service->sendReminder($visit, '24h');
            }
        }
        
        // 1h reminders
        $visits1h = PropertyVisit::whereBetween('visit_date', [
            now()->addMinutes(55),
            now()->addMinutes(65)
        ])->get();
        
        foreach ($visits1h as $visit) {
            if ($visit->status === 'confirmed') {
                $service->sendReminder($visit, '1h');
            }
        }
    }
}