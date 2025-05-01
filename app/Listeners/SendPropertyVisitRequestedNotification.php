<?php

namespace App\Listeners;

use App\Events\PropertyVisitRequested;
use App\Models\User;
use App\Notifications\PropertyVisitRequestedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPropertyVisitRequestedNotification implements ShouldQueue
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
    public function handle(PropertyVisitRequested $event): void
    {
        $visit = $event->visit;
        
        // Notifier l'agent assigné à la visite
        if ($visit->agent_id) {
            $agent = User::whereHas('agent', function ($query) use ($visit) {
                $query->where('id', $visit->agent_id);
            })->first();
            
            if ($agent) {
                $agent->notify(new PropertyVisitRequestedNotification($visit));
            }
        }
        
        // Notifier les administrateurs de l'agence
        if ($visit->property && $visit->property->agency_id) {
            $agencyAdmins = User::whereHas('agent', function ($query) use ($visit) {
                $query->where('agency_id', $visit->property->agency_id);
            })->whereHas('roles', function ($query) {
                $query->where('slug', 'agency_admin');
            })->get();
            
            foreach ($agencyAdmins as $admin) {
                $admin->notify(new PropertyVisitRequestedNotification($visit));
            }
        }
    }
}