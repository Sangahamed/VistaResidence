<?php

namespace App\Events;

use App\Models\ActivityLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;

    public function __construct(ActivityLog $activity)
    {
        $this->activity = $activity->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('user-activity');
    }

    public function broadcastAs()
    {
        return 'UserActivityLogged';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->activity->id,
            'user' => $this->activity->user?->name,
            'action' => $this->activity->action,
            'ip_address' => $this->activity->ip_address,
            'is_suspicious' => $this->activity->is_suspicious,
            'created_at' => $this->activity->created_at->toIso8601String(),
        ];
    }
}
