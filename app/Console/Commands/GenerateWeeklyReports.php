<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Property;
use App\Models\Lead;
use App\Models\PropertyVisit;
use App\Models\User;
use App\Notifications\WeeklyReportNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GenerateWeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-weekly-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère et envoie des rapports hebdomadaires aux administrateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Génération des rapports hebdomadaires...');
        
        $startDate = Carbon::now()->subWeek()->startOfWeek();
        $endDate = Carbon::now()->subWeek()->endOfWeek();
        
        // Générer des rapports pour chaque agence
        $agencies = Agency::all();
        foreach ($agencies as $agency) {
            $this->generateAgencyReport($agency, $startDate, $endDate);
        }
        
        // Générer des rapports pour chaque entreprise
        $companies = Company::all();
        foreach ($companies as $company) {
            $this->generateCompanyReport($company, $startDate, $endDate);
        }
        
        // Générer un rapport global pour les administrateurs
        $this->generateGlobalReport($startDate, $endDate);
        
        $this->info('Génération des rapports hebdomadaires terminée.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Génère un rapport hebdomadaire pour une agence.
     */
    private function generateAgencyReport(Agency $agency, Carbon $startDate, Carbon $endDate)
    {
        $this->info("Génération du rapport pour l'agence: {$agency->name}");
        
        // Statistiques des propriétés
        $newProperties = Property::where('agency_id', $agency->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $publishedProperties = Property::where('agency_id', $agency->id)
            ->where('status', 'published')
            ->whereBetween('published_at', [$startDate, $endDate])
            ->count();
            
        $soldProperties = Property::where('agency_id', $agency->id)
            ->where('status', 'sold')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        // Statistiques des leads
        $newLeads = Lead::where('agency_id', $agency->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        // Statistiques des visites
        $scheduledVisits = PropertyVisit::whereHas('property', function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $completedVisits = PropertyVisit::whereHas('property', function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        // Créer le rapport
        $reportData = [
            'agency' => $agency->toArray(),
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'properties' => [
                'new' => $newProperties,
                'published' => $publishedProperties,
                'sold' => $soldProperties,
            ],
            'leads' => [
                'new' => $newLeads,
            ],
            'visits' => [
                'scheduled' => $scheduledVisits,
                'completed' => $completedVisits,
            ],
        ];
        
        // Sauvegarder le rapport
        $filename = "reports/weekly/agencies/{$agency->id}/{$startDate->format('Y-m-d')}_to_{$endDate->format('Y-m-d')}.json";
        Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        // Envoyer le rapport aux administrateurs de l'agence
        $agencyAdmins = User::whereHas('agent', function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->whereHas('roles', function ($query) {
                $query->where('slug', 'agency_admin');
            })
            ->get();
            
        foreach ($agencyAdmins as $admin) {
            $admin->notify(new WeeklyReportNotification('agency', $reportData, $filename));
        }
        
        $this->info("Rapport généré pour l'agence: {$agency->name}");
    }
    
    /**
     * Génère un rapport hebdomadaire pour une entreprise.
     */
    private function generateCompanyReport(Company $company, Carbon $startDate, Carbon $endDate)
    {
        $this->info("Génération du rapport pour l'entreprise: {$company->name}");
        
        // Statistiques des propriétés
        $newProperties = Property::where('company_id', $company->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $publishedProperties = Property::where('company_id', $company->id)
            ->where('status', 'published')
            ->whereBetween('published_at', [$startDate, $endDate])
            ->count();
            
        $soldProperties = Property::where('company_id', $company->id)
            ->where('status', 'sold')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        // Statistiques des leads
        $newLeads = Lead::where('company_id', $company->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        // Statistiques des visites
        $scheduledVisits = PropertyVisit::whereHas('property', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $completedVisits = PropertyVisit::whereHas('property', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        // Créer le rapport
        $reportData = [
            'company' => $company->toArray(),
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'properties' => [
                'new' => $newProperties,
                'published' => $publishedProperties,
                'sold' => $soldProperties,
            ],
            'leads' => [
                'new' => $newLeads,
            ],
            'visits' => [
                'scheduled' => $scheduledVisits,
                'completed' => $completedVisits,
            ],
        ];
        
        // Sauvegarder le rapport
        $filename = "reports/weekly/companies/{$company->id}/{$startDate->format('Y-m-d')}_to_{$endDate->format('Y-m-d')}.json";
        Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        // Envoyer le rapport aux administrateurs de l'entreprise
        $companyAdmins = User::whereHas('companies', function ($query) use ($company) {
                $query->where('companies.id', $company->id)
                    ->wherePivot('is_admin', true);
            })
            ->get();
            
        foreach ($companyAdmins as $admin) {
            $admin->notify(new WeeklyReportNotification('company', $reportData, $filename));
        }
        
        $this->info("Rapport généré pour l'entreprise: {$company->name}");
    }
    
    /**
     * Génère un rapport hebdomadaire global pour les administrateurs.
     */
    private function generateGlobalReport(Carbon $startDate, Carbon $endDate)
    {
        $this->info("Génération du rapport global");
        
        // Statistiques des propriétés
        $newProperties = Property::whereBetween('created_at', [$startDate, $endDate])->count();
        $publishedProperties = Property::where('status', 'published')
            ->whereBetween('published_at', [$startDate, $endDate])
            ->count();
        $soldProperties = Property::where('status', 'sold')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        // Statistiques des leads
        $newLeads = Lead::whereBetween('created_at', [$startDate, $endDate])->count();
            
        // Statistiques des visites
        $scheduledVisits = PropertyVisit::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedVisits = PropertyVisit::where('status', 'completed')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
            
        // Statistiques des utilisateurs
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Créer le rapport
        $reportData = [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'properties' => [
                'new' => $newProperties,
                'published' => $publishedProperties,
                'sold' => $soldProperties,
                'total' => Property::count(),
            ],
            'leads' => [
                'new' => $newLeads,
                'total' => Lead::count(),
            ],
            'visits' => [
                'scheduled' => $scheduledVisits,
                'completed' => $completedVisits,
                'total' => PropertyVisit::count(),
            ],
            'users' => [
                'new' => $newUsers,
                'total' => User::count(),
            ],
            'agencies' => [
                'total' => Agency::count(),
            ],
            'companies' => [
                'total' => Company::count(),
            ],
        ];
        
        // Sauvegarder le rapport
        $filename = "reports/weekly/global/{$startDate->format('Y-m-d')}_to_{$endDate->format('Y-m-d')}.json";
        Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        // Envoyer le rapport aux administrateurs
        $admins = User::whereHas('roles', function ($query) {
                $query->whereIn('slug', ['admin', 'super_admin']);
            })
            ->get();
            
        foreach ($admins as $admin) {
            $admin->notify(new WeeklyReportNotification('global', $reportData, $filename));
        }
        
        $this->info("Rapport global généré");
    }
}