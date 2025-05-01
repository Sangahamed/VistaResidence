<?php

namespace App\Events;

use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyVisitRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visit;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(PropertyVisit $visit, User $user)
    {
        $this->visit = $visit;
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
        if ($this->visit->agent_id) {
            $channels[] = new PrivateChannel('agent.' . $this->visit->agent_id);
        }
        
        // Ajouter le canal de l'agence
        if ($this->visit->property && $this->visit->property->agency_id) {
            $channels[] = new PrivateChannel('agency.' . $this->visit->property->agency_id);
        }
        
        return $channels;
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'property.visit.requested';
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->visit->id,
            'property' => [
                'id' => $this->visit->property->id,
                'title' => $this->visit->property->title,
                'address' => $this->visit->property->address,
            ],
            'requested_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone ?? null,
            ],
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}