<?php

namespace App\Services;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyNotification;
use App\Models\SavedSearch;
use App\Notifications\PropertyAlert;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Create a new property notification.
     */
    public function createPropertyNotification(User $user, $type, Property $property, $message, $data = null)
    {
        return PropertyNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'property_id' => $property->id,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Notify users about a new property.
     */
    public function notifyNewProperty(Property $property)
    {
        // Find users with saved searches matching this property
        $savedSearches = SavedSearch::all();
        
        foreach ($savedSearches as $savedSearch) {
            $user = $savedSearch->user;
            $preferences = $user->notificationPreference;
            
            // Skip if user has disabled new property alerts
            if (!$preferences || !$preferences->new_property_alerts) {
                continue;
            }
            
            // Check if property matches saved search criteria
            if ($this->propertyMatchesSavedSearch($property, $savedSearch)) {
                // Create in-app notification
                $this->createPropertyNotification(
                    $user,
                    'new_property',
                    $property,
                    "Nouvelle propriété correspondant à vos critères : {$property->title}",
                    ['saved_search_id' => $savedSearch->id]
                );
                
                // Send email notification if enabled
                if ($preferences->email_notifications) {
                    $user->notify(new PropertyAlert($property, 'new_property', $savedSearch));
                }
            }
        }
    }

    /**
     * Notify users about a price change.
     */
    public function notifyPriceChange(Property $property, $oldPrice, $newPrice)
    {
        // Find users who have favorited this property
        $users = $property->favoritedBy;
        
        foreach ($users as $user) {
            $preferences = $user->notificationPreference;
            
            // Skip if user has disabled price change alerts
            if (!$preferences || !$preferences->price_change_alerts) {
                continue;
            }
            
            // Create in-app notification
            $this->createPropertyNotification(
                $user,
                'price_change',
                $property,
                "Le prix de {$property->title} a changé de {$oldPrice}€ à {$newPrice}€",
                ['old_price' => $oldPrice, 'new_price' => $newPrice]
            );
            
            // Send email notification if enabled
            if ($preferences->email_notifications) {
                $user->notify(new PropertyAlert($property, 'price_change', null, [
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice
                ]));
            }
        }
    }

    /**
     * Notify users about a status change.
     */
    public function notifyStatusChange(Property $property, $oldStatus, $newStatus)
    {
        // Find users who have favorited this property
        $users = $property->favoritedBy;
        
        foreach ($users as $user) {
            $preferences = $user->notificationPreference;
            
            // Skip if user has disabled status change alerts
            if (!$preferences || !$preferences->status_change_alerts) {
                continue;
            }
            
            // Create in-app notification
            $this->createPropertyNotification(
                $user,
                'status_change',
                $property,
                "Le statut de {$property->title} a changé de {$oldStatus} à {$newStatus}",
                ['old_status' => $oldStatus, 'new_status' => $newStatus]
            );
            
            // Send email notification if enabled
            if ($preferences->email_notifications) {
                $user->notify(new PropertyAlert($property, 'status_change', null, [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]));
            }
        }
    }

    /**
     * Check if a property matches a saved search criteria.
     */
    private function propertyMatchesSavedSearch(Property $property, SavedSearch $savedSearch)
    {
        $criteria = json_decode($savedSearch->criteria, true);
        
        // Basic matching logic - can be expanded for more complex criteria
        if (isset($criteria['property_type']) && $criteria['property_type'] != $property->property_type) {
            return false;
        }
        
        if (isset($criteria['min_price']) && $property->price < $criteria['min_price']) {
            return false;
        }
        
        if (isset($criteria['max_price']) && $property->price > $criteria['max_price']) {
            return false;
        }
        
        if (isset($criteria['min_bedrooms']) && $property->bedrooms < $criteria['min_bedrooms']) {
            return false;
        }
        
        if (isset($criteria['min_bathrooms']) && $property->bathrooms < $criteria['min_bathrooms']) {
            return false;
        }
        
        if (isset($criteria['location']) && !str_contains(strtolower($property->city), strtolower($criteria['location']))) {
            return false;
        }
        
        return true;
    }
}