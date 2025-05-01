<?php

namespace App\Events;

use App\Models\Property;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $property;
    public $user;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Property $property, User $user, string $oldStatus, string $newStatus)
    {
        $this->property = $property;
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
            new PrivateChannel('property.' . $this->property->id),
        ];
        
        // Ajouter le canal de l'agent si assigné
        if ($this->property->agent_id) {
            $channels[] = new PrivateChannel('agent.' . $this->property->agent_id);
        }
        
        // Ajouter le canal de l'agence
        if ($this->property->agency_id) {
            $channels[] = new PrivateChannel('agency.' . $this->property->agency_id);
        }
        
        // Ajouter le canal de l'entreprise
        if ($this->property->company_id) {
            $channels[] = new PrivateChannel('company.' . $this->property->company_id);
        }
        
        return $channels;
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'property.status.changed';
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->property->id,
            'title' => $this->property->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}