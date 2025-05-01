<?php

namespace App\Policies;

use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyVisitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur authentifié peut voir la liste des visites
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PropertyVisit $visit): bool
    {
        // L'utilisateur qui a demandé la visite peut la voir
        if ($visit->user_id === $user->id) {
            return true;
        }
        
        // L'agent assigné à la visite peut la voir
        if ($visit->agent_id && $user->agent && $visit->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut voir les visites de son agence
        if ($user->isAgencyAdmin() && $user->agent && $visit->property && $visit->property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut voir les visites des propriétés de son entreprise
        if ($user->isCompanyAdmin() && $visit->property && $visit->property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($visit->property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Tout utilisateur authentifié peut demander une visite
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PropertyVisit $visit): bool
    {
        // L'utilisateur qui a demandé la visite peut la modifier
        if ($visit->user_id === $user->id) {
            return true;
        }
        
        // L'agent assigné à la visite peut la modifier
        if ($visit->agent_id && $user->agent && $visit->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut modifier les visites de son agence
        if ($user->isAgencyAdmin() && $user->agent && $visit->property && $visit->property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut modifier les visites des propriétés de son entreprise
        if ($user->isCompanyAdmin() && $visit->property && $visit->property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($visit->property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PropertyVisit $visit): bool
    {
        // L'utilisateur qui a demandé la visite peut la supprimer
        if ($visit->user_id === $user->id) {
            return true;
        }
        
        // Un admin d'agence peut supprimer les visites de son agence
        if ($user->isAgencyAdmin() && $user->agent && $visit->property && $visit->property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut supprimer les visites des propriétés de son entreprise
        if ($user->isCompanyAdmin() && $visit->property && $visit->property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($visit->property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PropertyVisit $visit): bool
    {
        // Même logique que pour delete
        return $this->delete($user, $visit);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PropertyVisit $visit): bool
    {
        // Seul un admin ou super admin peut supprimer définitivement une visite
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can confirm the visit.
     */
    public function confirm(User $user, PropertyVisit $visit): bool
    {
        // L'agent assigné à la visite peut la confirmer
        if ($visit->agent_id && $user->agent && $visit->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut confirmer les visites de son agence
        if ($user->isAgencyAdmin() && $user->agent && $visit->property && $visit->property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut confirmer les visites des propriétés de son entreprise
        if ($user->isCompanyAdmin() && $visit->property && $visit->property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($visit->property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can complete the visit.
     */
    public function complete(User $user, PropertyVisit $visit): bool
    {
        // Même logique que pour confirm
        return $this->confirm($user, $visit);
    }
    
    /**
     * Determine whether the user can cancel the visit.
     */
    public function cancel(User $user, PropertyVisit $visit): bool
    {
        // L'utilisateur qui a demandé la visite peut l'annuler
        if ($visit->user_id === $user->id) {
            return true;
        }
        
        // Même logique que pour confirm pour les autres rôles
        return $this->confirm($user, $visit);
    }
    
    /**
     * Determine whether the user can add a note to the visit.
     */
    public function addNote(User $user, PropertyVisit $visit): bool
    {
        // L'agent assigné à la visite peut ajouter une note
        if ($visit->agent_id && $user->agent && $visit->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut ajouter une note aux visites de son agence
        if ($user->isAgencyAdmin() && $user->agent && $visit->property && $visit->property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut ajouter une note aux visites des propriétés de son entreprise
        if ($user->isCompanyAdmin() && $visit->property && $visit->property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($visit->property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
}