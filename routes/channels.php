<?php

use App\Models\User;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Property;
use App\Models\Lead;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Définition des canaux de diffusion pour les événements en temps réel.
|
*/

// 🔹 Canal pour les notifications utilisateur
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 🔹 Canal pour les notifications d'agence
Broadcast::channel('agency.{id}', function ($user, $id) {
    $agency = Agency::find($id);

    return $user->agent && $user->agent->agency_id === $id
        || $user->isAdmin()
        || $user->isSuperAdmin()
        || ($agency && $user->isCompanyAdminOf($agency->company_id));
});

// 🔹 Canal pour les notifications d'agent
Broadcast::channel('agent.{id}', function ($user, $id) {
    $agent = \App\Models\Agent::find($id);

    return $user->agent && $user->agent->id === $id
        || ($agent && $user->isAgencyAdmin() && $user->agent->agency_id === $agent->agency_id)
        || $user->isAdmin()
        || $user->isSuperAdmin();
});

// 🔹 Canal pour les notifications d'entreprise
Broadcast::channel('company.{id}', function ($user, $id) {
    return $user->companies()->where('companies.id', $id)->exists()
        || $user->isAdmin()
        || $user->isSuperAdmin();
});

// 🔹 Canal pour les notifications de propriété
Broadcast::channel('property.{id}', function ($user, $id) {
    $property = Property::find($id);

    return $property
        && (
            $property->user_id === $user->id
            || ($property->agent_id && $user->agent && $property->agent_id === $user->agent->id)
            || ($property->agency_id && $user->agent && $user->isAgencyAdmin() && $property->agency_id === $user->agent->agency_id)
            || ($property->company_id && $user->isCompanyAdminOf($property->company_id))
            || $user->isAdmin()
            || $user->isSuperAdmin()
        );
});

// 🔹 Canal pour les notifications de lead
Broadcast::channel('lead.{id}', function ($user, $id) {
    $lead = Lead::find($id);

    return $lead
        && (
            ($lead->agent_id && $user->agent && $lead->agent_id === $user->agent->id)
            || ($lead->agency_id && $user->agent && $user->isAgencyAdmin() && $lead->agency_id === $user->agent->agency_id)
            || ($lead->company_id && $user->isCompanyAdminOf($lead->company_id))
            || $user->isAdmin()
            || $user->isSuperAdmin()
        );
});
