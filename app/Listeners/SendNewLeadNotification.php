<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Models\User;
use App\Notifications\NewLeadNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewLeadNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadCreated $event): void
    {
        $lead = $event->lead;
        
        // Notifier l'agent assigné au lead
        if ($lead->agent_id) {
            $agent = User::whereHas('agent', function ($query) use ($lead) {
                $query->where('id', $lead->agent_id);
            })->first();
            
            if ($agent) {
                $agent->notify(new NewLeadNotification($lead));
            }
        }
        
        // Notifier les administrateurs de l'agence
        if ($lead->agency_id) {
            $agencyAdmins = User::whereHas('agent', function ($query) use ($lead) {
                $query->where('agency_id', $lead->agency_id);
            })->whereHas('roles', function ($query) {
                $query->where('slug', 'agency_admin');
            })->get();
            
            foreach ($agencyAdmins as $admin) {
                $admin->notify(new NewLeadNotification($lead));
            }
        }
        
        // Notifier les administrateurs de l'entreprise
        if ($lead->company_id) {
            $companyAdmins = User::whereHas('companies', function ($query) use ($lead) {
                $query->where('companies.id', $lead->company_id)
                    ->wherePivot('is_admin', true);
            })->get();
            
            foreach ($companyAdmins as $admin) {
                $admin->notify(new NewLeadNotification($lead));
            }
        }
    }
}