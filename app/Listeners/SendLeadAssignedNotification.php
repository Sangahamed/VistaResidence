<?php

namespace App\Listeners;

use App\Events\LeadAssigned;
use App\Models\User;
use App\Notifications\LeadAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLeadAssignedNotification implements ShouldQueue
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
    public function handle(LeadAssigned $event): void
    {
        $lead = $event->lead;
        $agent = $event->agent;
        $assignedBy = $event->assignedBy;
        
        // Notifier l'agent assigné
        $agentUser = $agent->user;
        if ($agentUser) {
            $agentUser->notify(new LeadAssignedNotification($lead, $agent, $assignedBy));
        }
    }
}