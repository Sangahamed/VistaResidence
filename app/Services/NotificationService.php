<?php

namespace App\Services;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Models\SavedSearch;

class NotificationService
{
    // PROPERTIES
    public function notifyNewProperty(Property $property)
    {
        if ($this->shouldNotify($property->owner, 'properties', 'new')) {
            $this->sendNotification(
                $property->owner,
                \App\Notifications\PropertyCreated::class,
                $property
            );
        }
    }

    public function notifyPriceChange(Property $property, $oldPrice, $newPrice)
    {
        $usersToNotify = $property->favoritedBy->push($property->owner);
        
        foreach ($usersToNotify as $user) {
            if ($this->shouldNotify($user, 'properties', 'price_change')) {
                $user->notify(new \App\Notifications\PropertyPriceChanged($property, $oldPrice, $newPrice));
            }
        }
    }

     // Notification changement de statut
    public function notifyStatusChange(Property $property, $oldStatus, $newStatus)
    {
        $usersToNotify = $property->favoritedBy->push($property->owner);
        
        foreach ($usersToNotify as $user) {
            if ($this->shouldNotify($user, 'properties', 'status_change')) {
                $user->notify(new \App\Notifications\PropertyStatusChanged($property, $oldStatus, $newStatus));
            }
        }
    }

    public function notifyPropertyUpdate(Property $property, array $changes)
    {
        if ($this->shouldNotify($property->owner, 'properties', 'updated')) {
            $this->sendNotification(
                $property->owner,
                \App\Notifications\PropertyUpdated::class,
                [$property, $changes]
            );
        }
    }

    

    // VISITS
    public function notifyNewVisit(PropertyVisit $visit)
    {
        // Notify owner
        if ($this->shouldNotify($visit->property->owner, 'visits', 'requested')) {
            $this->sendNotification(
                $visit->property->owner,
                \App\Notifications\VisitRequested::class,
                $visit
            );
        }

        // Notify assigned agent if different from owner
        if ($visit->agent && $visit->agent->id !== $visit->property->owner_id) {
            if ($this->shouldNotify($visit->agent, 'visits', 'requested')) {
                $this->sendNotification(
                    $visit->agent,
                    \App\Notifications\VisitRequested::class,
                    $visit
                );
            }
        }
    }

    public function notifyVisitStatusChanged(PropertyVisit $visit, $oldStatus, $newStatus)
{
    $notificationClass = match($newStatus) {
        'confirmed' => \App\Notifications\VisitConfirmed::class,
        'cancelled' => \App\Notifications\VisitCancelled::class,
        default => \App\Notifications\VisitStatusChanged::class
    };

    // Notifier le visiteur
    if ($this->shouldNotify($visit->visitor, 'visits', 'status_changes')) {
        $visit->visitor->notify(new $notificationClass($visit, $oldStatus, $newStatus));
    }

    // Notifier le propriétaire/agent
    $recipient = $visit->agent ?? $visit->property->owner;
    if ($this->shouldNotify($recipient, 'visits', 'status_changes')) {
        $recipient->notify(new $notificationClass($visit, $oldStatus, $newStatus));
    }
}

    public function notifyAddedToFavorites(Property $property, User $user)
{
    if ($this->shouldNotify($property->owner, 'favorites', 'added')) {
        $property->owner->notify(new \App\Notifications\AddedToFavorites($property, $user));
    }
}

    // SEARCHES
    public function notifySearchMatches(SavedSearch $search, $properties)
    {
        if ($this->shouldNotify($search->user, 'searches', 'new_matches')) {
            $this->sendNotification(
                $search->user,
                \App\Notifications\SearchMatchesFound::class,
                [$search, $properties]
            );
        }
    }

    // HELPER METHODS
    protected function shouldNotify(User $user, $category, $type)
    {
        return $user->notificationPreference->shouldNotify($category, $type);
    }

    protected function sendNotification(User $user, $notificationClass, $parameters)
    {
        $user->notify(
            is_array($parameters) 
                ? new $notificationClass(...$parameters)
                : new $notificationClass($parameters)
        );
    }
}