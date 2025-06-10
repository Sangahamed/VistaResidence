<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportGenerator;
use App\Mail\ActivityReport;
use Illuminate\Support\Facades\Mail;

class GenerateMonthlyReport extends Command
{
    protected $signature = 'report:monthly';
    protected $description = 'Génère et envoie le rapport d\'activité mensuel';

    public function handle(ReportGenerator $reportGenerator)
    {
        if (!config('activity.reports.monthly.enabled')) {
            $this->info('Les rapports mensuels sont désactivés.');
            return;
        }

        $this->info('Génération du rapport mensuel...');
        
        $startDate = now()->subMonth()->startOfMonth();
        $endDate = now()->subMonth()->endOfMonth();
        
        $report = $reportGenerator->generate($startDate, $endDate, 'monthly');
        
        $recipients = config('activity.reports.monthly.recipients');
        
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new ActivityReport($report, 'monthly'));
        }
        
        $this->info('Rapport mensuel envoyé à ' . implode(', ', $recipients));
    }
}
