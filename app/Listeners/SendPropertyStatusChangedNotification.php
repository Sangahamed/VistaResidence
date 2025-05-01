<?php

namespace App\Listeners;

use App\Events\PropertyStatusChanged;
use App\Models\User;
use App\Notifications\PropertyStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPropertyStatusChangedNotification implements ShouldQueue
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
    public function handle(PropertyStatusChanged $event): void
    {
        $property = $event->property;
        
        // Notifier le propriétaire de la propriété
        if ($property->user_id) {
            $owner = User::find($property->user_id);
            if ($owner) {
                $owner->notify(new PropertyStatusChangedNotification(
                    $property,
                    $event->oldStatus,
                    $event->newStatus
                ));
            }
        }
        
        // Notifier l'agent assigné à la propriété
        if ($property->agent_id) {
            $agent = User::whereHas('agent', function ($query) use ($property) {
                $query->where('id', $property->agent_id);
            })->first();
            
            if ($agent) {
                $agent->notify(new PropertyStatusChangedNotification(
                    $property,
                    $event->oldStatus,
                    $event->newStatus
                ));
            }
        }
        
        // Notifier les administrateurs de l'agence
        if ($property->agency_id) {
            $agencyAdmins = User::whereHas('agent', function ($query) use ($property) {
                $query->where('agency_id', $property->agency_id);
            })->whereHas('roles', function ($query) {
                $query->where('slug', 'agency_admin');
            })->get();
            
            foreach ($agencyAdmins as $admin) {
                $admin->notify(new PropertyStatusChangedNotification(
                    $property,
                    $event->oldStatus,
                    $event->newStatus
                ));
            }
        }
    }
}