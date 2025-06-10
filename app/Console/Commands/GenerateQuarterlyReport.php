<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportGenerator;
use App\Mail\ActivityReport;
use Illuminate\Support\Facades\Mail;

class GenerateQuarterlyReport extends Command
{
    protected $signature = 'report:quarterly';
    protected $description = 'Génère et envoie le rapport d\'activité trimestriel';

    public function handle(ReportGenerator $reportGenerator)
    {
        if (!config('activity.reports.quarterly.enabled')) {
            $this->info('Les rapports trimestriels sont désactivés.');
            return;
        }

        $this->info('Génération du rapport trimestriel...');
        
        // Déterminer le trimestre précédent
        $currentMonth = now()->month;
        $currentQuarter = ceil($currentMonth / 3);
        $previousQuarter = $currentQuarter - 1;
        
        if ($previousQuarter <= 0) {
            $previousQuarter = 4;
            $year = now()->year - 1;
        } else {
            $year = now()->year;
        }
        
        $startMonth = ($previousQuarter - 1) * 3 + 1;
        
        $startDate = now()->setDate($year, $startMonth, 1)->startOfDay();
        $endDate = $startDate->copy()->addMonths(3)->subDay()->endOfDay();
        
        $report = $reportGenerator->generate($startDate, $endDate, 'quarterly');
        
        $recipients = config('activity.reports.quarterly.recipients');
        
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new ActivityReport($report, 'quarterly'));
        }
        
        $this->info('Rapport trimestriel envoyé à ' . implode(', ', $recipients));
    }
}
