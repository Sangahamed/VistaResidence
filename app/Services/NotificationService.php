<?php

namespace App\Services;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Models\SavedSearch;
use App\Notifications\PropertyUpdatedNotification;
use App\Models\Company;


class NotificationService
{

    public function notifyCompanyStatusChanged(Company $company)
    {
        $user = $company->owner;
        $status = $company->status;
        
        if ($status === 'approved') {
            $user->notify(new \App\Notifications\CompanyApproved($company));
        } elseif ($status === 'rejected') {
            $user->notify(new \App\Notifications\CompanyRejected($company));
        }
    }
    
    // PROPERTIES
    public function notifyNewProperty(Property $property)
    {
        if ($this->shouldNotify($property->owner, 'properties', 'new')) {
            $this->sendNotification(
                $property->owner,
                \App\Notifications\NewPropertyNotification::class,
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
                $user->notify(new \App\Notifications\PropertyVisitStatusChangedNotification($property, $oldStatus, $newStatus));
            }
        }
    }

   
    public function notifyPropertyUpdate(Property $property, array $changes)
    {
        $owner = $property->owner;
        $owner->notify(new PropertyUpdatedNotification($property, $changes));

        // Notifier les favoris
        $favoritedBy = $property->favoritedBy()->with('notificationPreference')->get();
        
        foreach ($favoritedBy as $user) {
            if ($user->notificationPreference?->property_updates) {
                $user->notify(new PropertyUpdatedNotification($property, $changes));
            }
        }
    }

    

    // VISITS
    public function notifyNewVisit(PropertyVisit $visit)
    {
        // Notify owner
         // Vérifier si la visite est liée à une propriété avant d'envoyer la notification
        if ($visit->property && $this->shouldNotify($visit->property->owner, 'visits', 'requested')) {
            $this->sendNotification(
                $visit->property->owner,
                \App\Notifications\VisitRequested::class,
                $visit
            );
        }

        // Notify assigned agent if different from owner
        if ($visit->property  && $visit->agent && $visit->agent->id !== $visit->property->owner_id) {
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

//     public function notifyAddedToFavorites(Property $property, User $user)
// {
//     if ($this->shouldNotify($property->owner, 'favorites', 'added')) {
//         $property->owner->notify(new \App\Notifications\PropertyFavorited($property, $user));
//     }
// }

    // SEARCHES
    public function notifySearchMatches(SavedSearch $search, $properties)
    {
        if ($this->shouldNotify($search->user, 'searches', 'new_matches')) {
            $this->sendNotification(
                $search->user,
                \App\Notifications\PropertyMatchNotification::class,
                [$search, $properties]
            );
        }
    }

     protected function canNotify(User $user, $category, $type)
    {
        return $user->notificationPreference->shouldNotify($category, $type);
    }

    
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