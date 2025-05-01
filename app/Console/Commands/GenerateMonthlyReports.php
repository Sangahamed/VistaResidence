<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Property;
use App\Models\Lead;
use App\Models\PropertyVisit;
use App\Models\User;
use App\Notifications\MonthlyReportNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-monthly-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère et envoie des rapports mensuels aux administrateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Génération des rapports mensuels...');
        
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();
        
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
        
        $this->info('Génération des rapports mensuels terminée.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Génère un rapport mensuel pour une agence.
     */
    private function generateAgencyReport(Agency $agency, Carbon $startDate, Carbon $endDate)
    {
        $this->info("Génération du rapport mensuel pour l'agence: {$agency->name}");
        
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
            
        // Statistiques des agents
        $agentPerformance = DB::table('property_visits')
            ->join('properties', 'property_visits.property_id', '=', 'properties.id')
            ->join('agents', 'property_visits.agent_id', '=', 'agents.id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->where('properties.agency_id', $agency->id)
            ->where('property_visits.status', 'completed')
            ->whereBetween('property_visits.updated_at', [$startDate, $endDate])
            ->select('agents.id', 'users.name', DB::raw('count(*) as completed_visits'))
            ->groupBy('agents.id', 'users.name')
            ->orderBy('completed_visits', 'desc')
            ->limit(5)
            ->get();
            
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
                'total' => Property::where('agency_id', $agency->id)->count(),
            ],
            'leads' => [
                'new' => $newLeads,
                'total' => Lead::where('agency_id', $agency->id)->count(),
            ],
            'visits' => [
                'scheduled' => $scheduledVisits,
                'completed' => $completedVisits,
                'total' => PropertyVisit::whereHas('property', function ($query) use ($agency) {
                    $query->where('agency_id', $agency->id);
                })->count(),
            ],
            'agent_performance' => $agentPerformance,
        ];
        
        // Sauvegarder le rapport
        $filename = "reports/monthly/agencies/{$agency->id}/{$startDate->format('Y-m')}.json";
        Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        // Générer un PDF du rapport
        $pdfFilename = "reports/monthly/agencies/{$agency->id}/{$startDate->format('Y-m')}.pdf";
        $this->generatePdfReport($reportData, $pdfFilename, 'agency');
        
        // Envoyer le rapport aux administrateurs de l'agence
        $agencyAdmins = User::whereHas('agent', function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->whereHas('roles', function ($query) {
                $query->where('slug', 'agency_admin');
            })
            ->get();
            
        foreach ($agencyAdmins as $admin) {
            $admin->notify(new MonthlyReportNotification('agency', $reportData, $pdfFilename));
        }
        
        $this->info("Rapport mensuel généré pour l'agence: {$agency->name}");
    }
    
    /**
     * Génère un rapport mensuel pour une entreprise.
     */
    private function generateCompanyReport(Company $company, Carbon $startDate, Carbon $endDate)
    {
        $this->info("Génération du rapport mensuel pour l'entreprise: {$company->name}");
        
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
            
        // Statistiques des agences
        $agencyPerformance = DB::table('properties')
            ->join('agencies', 'properties.agency_id', '=', 'agencies.id')
            ->where('properties.company_id', $company->id)
            ->where('properties.status', 'sold')
            ->whereBetween('properties.updated_at', [$startDate, $endDate])
            ->select('agencies.id', 'agencies.name', DB::raw('count(*) as sold_properties'))
            ->groupBy('agencies.id', 'agencies.name')
            ->orderBy('sold_properties', 'desc')
            ->get();
            
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
                'total' => Property::where('company_id', $company->id)->count(),
            ],
            'leads' => [
                'new' => $newLeads,
                'total' => Lead::where('company_id', $company->id)->count(),
            ],
            'visits' => [
                'scheduled' => $scheduledVisits,
                'completed' => $completedVisits,
                'total' => PropertyVisit::whereHas('property', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })->count(),
            ],
            'agency_performance' => $agencyPerformance,
        ];
        
        // Sauvegarder le rapport
        $filename = "reports/monthly/companies/{$company->id}/{$startDate->format('Y-m')}.json";
        Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        // Générer un PDF du rapport
        $pdfFilename = "reports/monthly/companies/{$company->id}/{$startDate->format('Y-m')}.pdf";
        $this->generatePdfReport($reportData, $pdfFilename, 'company');
        
        // Envoyer le rapport aux administrateurs de l'entreprise
        $companyAdmins = User::whereHas('companies', function ($query) use ($company) {
                $query->where('companies.id', $company->id)
                    ->wherePivot('is_admin', true);
            })
            ->get();
            
        foreach ($companyAdmins as $admin) {
            $admin->notify(new MonthlyReportNotification('company', $reportData, $pdfFilename));
        }
        
        $this->info("Rapport mensuel généré pour l'entreprise: {$company->name}");
    }
    
    /**
     * Génère un rapport mensuel global pour les administrateurs.
     */
    private function generateGlobalReport(Carbon $startDate, Carbon $endDate)
    {
        $this->info("Génération du rapport mensuel global");
        
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
        
        // Top 5 des agences par propriétés vendues
        $topAgencies = DB::table('properties')
            ->join('agencies', 'properties.agency_id', '=', 'agencies.id')
            ->where('properties.status', 'sold')
            ->whereBetween('properties.updated_at', [$startDate, $endDate])
            ->select('agencies.id', 'agencies.name', DB::raw('count(*) as sold_properties'))
            ->groupBy('agencies.id', 'agencies.name')
            ->orderBy('sold_properties', 'desc')
            ->limit(5)
            ->get();
            
        // Top 5 des agents par visites complétées
        $topAgents = DB::table('property_visits')
            ->join('agents', 'property_visits.agent_id', '=', 'agents.id')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->where('property_visits.status', 'completed')
            ->whereBetween('property_visits.updated_at', [$startDate, $endDate])
            ->select('agents.id', 'users.name', DB::raw('count(*) as completed_visits'))
            ->groupBy('agents.id', 'users.name')
            ->orderBy('completed_visits', 'desc')
            ->limit(5)
            ->get();
        
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
                'top' => $topAgencies,
            ],
            'companies' => [
                'total' => Company::count(),
            ],
            'agents' => [
                'top' => $topAgents,
            ],
        ];
        
        // Sauvegarder le rapport
        $filename = "reports/monthly/global/{$startDate->format('Y-m')}.json";
        Storage::put($filename, json_encode($reportData, JSON_PRETTY_PRINT));
        
        // Générer un PDF du rapport
        $pdfFilename = "reports/monthly/global/{$startDate->format('Y-m')}.pdf";
        $this->generatePdfReport($reportData, $pdfFilename, 'global');
        
        // Envoyer le rapport aux administrateurs
        $admins = User::whereHas('roles', function ($query) {
                $query->whereIn('slug', ['admin', 'super_admin']);
            })
            ->get();
            
        foreach ($admins as $admin) {
            $admin->notify(new MonthlyReportNotification('global', $reportData, $pdfFilename));
        }
        
        $this->info("Rapport mensuel global généré");
    }
    
    /**
     * Génère un PDF à partir des données du rapport.
     */
    private function generatePdfReport($reportData, $filename, $type)
    {
        // Utilisation d'une bibliothèque PDF comme DOMPDF
        $pdf = \App::make('dompdf.wrapper');
        $view = 'reports.monthly.' . $type;
        $pdf->loadView($view, ['report' => $reportData]);
        Storage::put($filename, $pdf->output());
    }
}