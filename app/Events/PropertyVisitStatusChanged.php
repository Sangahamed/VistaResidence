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

class PropertyVisitStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visit;
    public $user;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(PropertyVisit $visit, User $user, string $oldStatus, string $newStatus)
    {
        $this->visit = $visit;
        $this->user = $user;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            // Canal du client qui a demandé la visite
            new PrivateChannel('user.' . $this->visit->user_id),
        ];
        
        // Ajouter le canal de l'agent si assigné
        if ($this->visit->agent_id) {
            $channels[] = new PrivateChannel('agent.' . $this->visit->agent_id);
        }
        
        return $channels;
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'property.visit.status.changed';
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
            ],
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'scheduled_at' => $this->visit->scheduled_at->toIso8601String(),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}