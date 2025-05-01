<?php

namespace App\Listeners;

use App\Events\PropertyCreated;
use App\Models\User;
use App\Notifications\NewPropertyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewPropertyNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PropertyCreated $event): void
    {
        $property = $event->property;
        
        // Notifier les administrateurs de l'agence
        if ($property->agency_id) {
            $agencyAdmins = User::whereHas('agent', function ($query) use ($property) {
                $query->where('agency_id', $property->agency_id);
            })->whereHas('roles', function ($query) {
                $query->where('slug', 'agency_admin');
            })->get();
            
            foreach ($agencyAdmins as $admin) {
                $admin->notify(new NewPropertyNotification($property));
            }
        }
        
        // Notifier les administrateurs de l'entreprise
        if ($property->company_id) {
            $companyAdmins = User::whereHas('companies', function ($query) use ($property) {
                $query->where('companies.id', $property->company_id)
                    ->wherePivot('is_admin', true);
            })->get();
            
            foreach ($companyAdmins as $admin) {
                $admin->notify(new NewPropertyNotification($property));
            }
        }
    }
}