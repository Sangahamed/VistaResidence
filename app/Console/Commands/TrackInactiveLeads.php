<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Notifications\InactiveLeadNotification;
use Carbon\Carbon;

class TrackInactiveLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:track-inactive-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifie les leads inactifs et envoie des notifications aux agents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Suivi des leads inactifs...');
        
        // Définir les seuils d'inactivité (en jours)
        $warningThreshold = config('lead.warning_threshold', 7); // 7 jours
        $criticalThreshold = config('lead.critical_threshold', 14); // 14 jours
        
        $now = Carbon::now();
        $warningDate = $now->copy()->subDays($warningThreshold);
        $criticalDate = $now->copy()->subDays($criticalThreshold);
        
        // Trouver les leads avec un niveau d'inactivité "warning"
        $warningLeads = Lead::where('status', '!=', 'closed')
            ->where('status', '!=', 'converted')
            ->where(function ($query) use ($warningDate, $criticalDate) {
                // Leads qui n'ont pas d'activité récente
                $query->whereDoesntHave('activities', function ($q) use ($warningDate) {
                    $q->where('created_at', '>=', $warningDate);
                })
                // Et qui ont au moins une activité (pour exclure les nouveaux leads)
                ->whereHas('activities')
                // Et qui n'ont pas déjà été marqués comme inactifs
                ->where('inactive_level', '!=', 'warning')
                ->where('inactive_level', '!=', 'critical');
            })
            ->get();
            
        $this->info("Nombre de leads avec niveau d'inactivité 'warning' : " . $warningLeads->count());
        
        foreach ($warningLeads as $lead) {
            $lead->inactive_level = 'warning';
            $lead->save();
            
            // Créer une activité pour le lead
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => null, // Système
                'type' => 'system',
                'description' => "Lead marqué comme inactif (niveau: warning) - Aucune activité depuis {$warningThreshold} jours",
            ]);
            
            // Notifier l'agent assigné
            if ($lead->agent && $lead->agent->user) {
                $lead->agent->user->notify(new InactiveLeadNotification($lead, 'warning'));
                $this->info("Notification 'warning' envoyée à l'agent {$lead->agent->user->name} pour le lead {$lead->name}");
            }
        }
        
        // Trouver les leads avec un niveau d'inactivité "critical"
        $criticalLeads = Lead::where('status', '!=', 'closed')
            ->where('status', '!=', 'converted')
            ->where(function ($query) use ($criticalDate) {
                // Leads qui n'ont pas d'activité récente
                $query->whereDoesntHave('activities', function ($q) use ($criticalDate) {
                    $q->where('created_at', '>=', $criticalDate);
                })
                // Et qui ont au moins une activité (pour exclure les nouveaux leads)
                ->whereHas('activities')
                // Et qui n'ont pas déjà été marqués comme critiques
                ->where('inactive_level', '!=', 'critical');
            })
            ->get();
            
        $this->info("Nombre de leads avec niveau d'inactivité 'critical' : " . $criticalLeads->count());
        
        foreach ($criticalLeads as $lead) {
            $lead->inactive_level = 'critical';
            $lead->save();
            
            // Créer une activité pour le lead
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => null, // Système
                'type' => 'system',
                'description' => "Lead marqué comme inactif (niveau: critical) - Aucune activité depuis {$criticalThreshold} jours",
            ]);
            
            // Notifier l'agent assigné
            if ($lead->agent && $lead->agent->user) {
                $lead->agent->user->notify(new InactiveLeadNotification($lead, 'critical'));
                $this->info("Notification 'critical' envoyée à l'agent {$lead->agent->user->name} pour le lead {$lead->name}");
            }
            
            // Notifier également l'administrateur de l'agence
            if ($lead->agency_id) {
                $agencyAdmins = \App\Models\User::whereHas('agent', function ($query) use ($lead) {
                    $query->where('agency_id', $lead->agency_id);
                })->whereHas('roles', function ($query) {
                    $query->where('slug', 'agency_admin');
                })->get();
                
                foreach ($agencyAdmins as $admin) {
                    $admin->notify(new InactiveLeadNotification($lead, 'critical'));
                    $this->info("Notification 'critical' envoyée à l'administrateur {$admin->name} pour le lead {$lead->name}");
                }
            }
        }
        
        // Réinitialiser le niveau d'inactivité pour les leads qui ont eu une activité récente
        $reactivatedLeads = Lead::where('inactive_level', '!=', null)
            ->whereHas('activities', function ($query) use ($warningDate) {
                $query->where('created_at', '>=', $warningDate);
            })
            ->get();
            
        $this->info("Nombre de leads réactivés : " . $reactivatedLeads->count());
        
        foreach ($reactivatedLeads as $lead) {
            $oldLevel = $lead->inactive_level;
            $lead->inactive_level = null;
            $lead->save();
            
            // Créer une activité pour le lead
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => null, // Système
                'type' => 'system',
                'description' => "Lead réactivé - Précédemment marqué comme inactif (niveau: {$oldLevel})",
            ]);
            
            $this->info("Lead {$lead->name} réactivé");
        }
        
        $this->info('Suivi des leads inactifs terminé.');
        
        return Command::SUCCESS;
    }
}