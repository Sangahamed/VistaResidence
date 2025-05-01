<?php

namespace App\Listeners;

use App\Events\PropertyVisitStatusChanged;
use App\Models\User;
use App\Notifications\PropertyVisitStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPropertyVisitStatusChangedNotification implements ShouldQueue
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
    public function handle(PropertyVisitStatusChanged $event): void
    {
        $visit = $event->visit;
        
        // Notifier le client qui a demandé la visite
        if ($visit->user_id) {
            $client = User::find($visit->user_id);
            if ($client) {
                $client->notify(new PropertyVisitStatusChangedNotification(
                    $visit,
                    $event->oldStatus,
                    $event->newStatus
                ));
            }
        }
        
        // Notifier l'agent assigné à la visite
        if ($visit->agent_id) {
            $agent = User::whereHas('agent', function ($query) use ($visit) {
                $query->where('id', $visit->agent_id);
            })->first();
            
            if ($agent) {
                $agent->notify(new PropertyVisitStatusChangedNotification(
                    $visit,
                    $event->oldStatus,
                    $event->newStatus
                ));
            }
        }
    }
}