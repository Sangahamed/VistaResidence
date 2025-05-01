<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Property;
use App\Models\Lead;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Canal pour les notifications utilisateur
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal pour les notifications utilisateur (format alternatif)
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal pour les notifications d'agence
Broadcast::channel('agency.{id}', function ($user, $id) {
    // Vérifier si l'utilisateur est membre de l'agence
    if ($user->agent && $user->agent->agency_id == $id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur
    if ($user->isAdmin() || $user->isSuperAdmin()) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur d'entreprise
    $agency = Agency::find($id);
    if ($agency && $agency->company_id) {
        return $user->isCompanyAdminOf($agency->company_id);
    }
    
    return false;
});

// Canal pour les notifications d'agent
Broadcast::channel('agent.{id}', function ($user, $id) {
    // Vérifier si l'utilisateur est l'agent
    if ($user->agent && $user->agent->id == $id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur d'agence
    $agent = \App\Models\Agent::find($id);
    if ($agent && $user->agent && $user->isAgencyAdmin() && $user->agent->agency_id == $agent->agency_id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur
    if ($user->isAdmin() || $user->isSuperAdmin()) {
        return true;
    }
    
    return false;
});

// Canal pour les notifications d'entreprise
Broadcast::channel('company.{id}', function ($user, $id) {
    // Vérifier si l'utilisateur est membre de l'entreprise
    if ($user->companies()->where('companies.id', $id)->exists()) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur
    if ($user->isAdmin() || $user->isSuperAdmin()) {
        return true;
    }
    
    return false;
});

// Canal pour les notifications de propriété
Broadcast::channel('property.{id}', function ($user, $id) {
    $property = Property::find($id);
    if (!$property) {
        return false;
    }
    
    // Vérifier si l'utilisateur est le propriétaire
    if ($property->user_id == $user->id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est l'agent assigné
    if ($property->agent_id && $user->agent && $property->agent_id == $user->agent->id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur d'agence
    if ($property->agency_id && $user->agent && $user->isAgencyAdmin() && $property->agency_id == $user->agent->agency_id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur d'entreprise
    if ($property->company_id && $user->isCompanyAdminOf($property->company_id)) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur
    if ($user->isAdmin() || $user->isSuperAdmin()) {
        return true;
    }
    
    return false;
});

// Canal pour les notifications de lead
Broadcast::channel('lead.{id}', function ($user, $id) {
    $lead = Lead::find($id);
    if (!$lead) {
        return false;
    }
    
    // Vérifier si l'utilisateur est l'agent assigné
    if ($lead->agent_id && $user->agent && $lead->agent_id == $user->agent->id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur d'agence
    if ($lead->agency_id && $user->agent && $user->isAgencyAdmin() && $lead->agency_id == $user->agent->agency_id) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur d'entreprise
    if ($lead->company_id && $user->isCompanyAdminOf($lead->company_id)) {
        return true;
    }
    
    // Vérifier si l'utilisateur est un administrateur
    if ($user->isAdmin() || $user->isSuperAdmin()) {
        return true;
    }
    
    return false;
});