<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Chatify\Events\NewMessage;
use App\Listeners\SendChatMessageNotification;

use App\Events\PropertyCreated;
use App\Events\PropertyStatusChanged;
use App\Events\LeadCreated;
use App\Events\LeadAssigned;
use App\Events\PropertyVisitRequested;
use App\Events\PropertyVisitStatusChanged;

use App\Listeners\SendNewPropertyNotification;
use App\Listeners\SendPropertyStatusChangedNotification;
use App\Listeners\SendNewLeadNotification;
use App\Listeners\SendLeadAssignedNotification;
use App\Listeners\SendPropertyVisitRequestedNotification;
use App\Listeners\SendPropertyVisitStatusChangedNotification;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewMessage::class => [
            SendChatMessageNotification::class,
        ],
        // Événements liés aux propriétés
        PropertyCreated::class => [
            SendNewPropertyNotification::class,
        ],
        PropertyStatusChanged::class => [
            SendPropertyStatusChangedNotification::class,
        ],
        
        // Événements liés aux leads
        LeadCreated::class => [
            SendNewLeadNotification::class,
        ],
        LeadAssigned::class => [
            SendLeadAssignedNotification::class,
        ],
        
        // Événements liés aux visites de propriétés
        PropertyVisitRequested::class => [
            SendPropertyVisitRequestedNotification::class,
        ],
        PropertyVisitStatusChanged::class => [
            SendPropertyVisitStatusChangedNotification::class,
        ],
        'eloquent.created: App\Models\Property' => [
        'App\Observers\PropertyObserver@created',
        ],
        'eloquent.updated: App\Models\Property' => [
            'App\Observers\PropertyObserver@updated',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}