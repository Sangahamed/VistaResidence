<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Jenssegers\Agent\Agent;

trait LogsActivity
{
    protected function logActivity($action, $details = null)
    {
        $agent = new Agent();
        
        ActivityLog::create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => $action,
            'details' => is_array($details) ? json_encode($details) : $details,
            'user_id' => auth()->guard('web')->id(),
            'admin_id' => auth()->guard('admin')->id()
        ]);
    }
}