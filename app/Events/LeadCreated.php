<?php

namespace App\Events;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lead;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Lead $lead, User $user)
    {
        $this->lead = $lead;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        // Ajouter le canal de l'agent si assigné
        if ($this->lead->agent_id) {
            $channels[] = new PrivateChannel('agent.' . $this->lead->agent_id);
        }
        
        // Ajouter le canal de l'agence
        if ($this->lead->agency_id) {
            $channels[] = new PrivateChannel('agency.' . $this->lead->agency_id);
        }
        
        // Ajouter le canal de l'entreprise
        if ($this->lead->company_id) {
            $channels[] = new PrivateChannel('company.' . $this->lead->company_id);
        }
        
        return $channels;
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'lead.created';
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->lead->id,
            'name' => $this->lead->name,
            'email' => $this->lead->email,
            'phone' => $this->lead->phone,
            'source' => $this->lead->source,
            'created_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}