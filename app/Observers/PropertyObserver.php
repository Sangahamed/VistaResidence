<?php

namespace App\Observers;

use App\Models\Property;
use App\Services\PropertySearchService;
use App\Services\NotificationService;

class PropertyObserver
{
    protected $searchService;

    public function __construct(PropertySearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function created(Property $property)
    {
        app(NotificationService::class)->notifyNewProperty($property);

        if ($property->availability_status === 'active') {
            $this->searchService->checkForNewMatches($property);
        }
    }

     public function updated(Property $property)
    {
        $original = $property->getOriginal();
        $changes = $property->getChanges();

        // Notification changement de prix
        if (array_key_exists('price', $changes)) {
            app(NotificationService::class)->notifyPriceChange(
                $property,
                $original['price'],
                $changes['price']
            );
        }

        // Notification changement de statut
        if (array_key_exists('status', $changes)) {
            app(NotificationService::class)->notifyStatusChange(
                $property,
                $original['status'],
                $changes['status']
            );
        }
        
        if (count($changes) > 0) {
            app(NotificationService::class)->notifyPropertyUpdate($property, $changes);
        }

         if ($property->wasChanged('status') && $property->availability_status === 'active') {
            $this->searchService->checkForNewMatches($property);
        }
    }
}