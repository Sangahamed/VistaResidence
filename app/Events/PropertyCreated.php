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

class PropertyCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $property;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Property $property, User $user)
    {
        $this->property = $property;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Diffuser sur le canal de l'agence
        if ($this->property->agency_id) {
            return [
                new PrivateChannel('agency.' . $this->property->agency_id),
            ];
        }
        
        // Diffuser sur le canal de l'entreprise
        if ($this->property->company_id) {
            return [
                new PrivateChannel('company.' . $this->property->company_id),
            ];
        }
        
        return [];
    }
    
    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'property.created';
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
            'address' => $this->property->address,
            'price' => $this->property->price,
            'created_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}