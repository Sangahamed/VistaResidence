<?php

namespace App\Listeners;

use App\Events\SuspiciousActivityDetected;
use App\Notifications\SuspiciousActivityDetected as SuspiciousActivityNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class NotifySuspiciousActivity
{
    public function handle(SuspiciousActivityDetected $event): void
    {
        // Notifier les administrateurs
        $admins = User::role('admin')->get();
        
        Notification::send($admins, new SuspiciousActivityNotification($event->activity));
    }
}
