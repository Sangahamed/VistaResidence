<?php

namespace App\Observers;

use App\Models\PropertyVisit;
use App\Services\NotificationService;

class VisitObserver
{
    public function created(PropertyVisit $visit)
    {
        app(NotificationService::class)->notifyNewVisit($visit);
    }

    public function updated(PropertyVisit $visit)
    {
        if ($visit->isDirty('status')) {
            app(NotificationService::class)->notifyVisitStatusChanged(
                $visit,
                $visit->getOriginal('status'),
                $visit->status
            );
        }
    }
}