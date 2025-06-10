<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportGenerator;
use App\Mail\ActivityReport;
use Illuminate\Support\Facades\Mail;

class GenerateDailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Génère et envoie le rapport d\'activité journalier';

    public function handle(ReportGenerator $reportGenerator)
    {
        if (!config('activity.reports.daily.enabled')) {
            $this->info('Les rapports journaliers sont désactivés.');
            return;
        }

        $this->info('Génération du rapport journalier...');
        
        $startDate = now()->subDay()->startOfDay();
        $endDate = now()->subDay()->endOfDay();
        
        $report = $reportGenerator->generate($startDate, $endDate, 'daily');
        
        $recipients = config('activity.reports.daily.recipients');
        
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new ActivityReport($report, 'daily'));
        }
        
        $this->info('Rapport journalier envoyé à ' . implode(', ', $recipients));
    }
}
