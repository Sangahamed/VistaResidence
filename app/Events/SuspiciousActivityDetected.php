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

class SuspiciousActivityDetected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;

    public function __construct(ActivityLog $activity)
    {
        $this->activity = $activity->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('suspicious-activity');
    }

    public function broadcastAs()
    {
        return 'SuspiciousActivityDetected';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->activity->id,
            'risk_score' => $this->activity->risk_score,
            'type' => $this->activity->suspicion_type,
            'user' => $this->activity->user?->name,
            'action' => $this->activity->action,
            'time' => $this->activity->created_at->diffForHumans(),
            'recommendation' => $this->activity->metadata['ai_analysis']['recommendation'] ?? null
        ];
    }
}
