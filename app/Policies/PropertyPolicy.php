<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur authentifié peut voir la liste des propriétés
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        // Tout utilisateur authentifié peut voir une propriété
        // sauf si elle est en statut brouillon ou non publiée
        if ($property->status === 'draft' || !$property->is_published) {
            return $this->update($user, $property);
        }
        
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Un agent, admin d'agence, admin d'entreprise, admin ou super admin peut créer une propriété
        return $user->isAgent() || $user->isAgencyAdmin() || $user->isCompanyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        // Le propriétaire de la propriété peut la modifier
        if ($property->user_id === $user->id) {
            return true;
        }
        
        // L'agent assigné à la propriété peut la modifier
        if ($property->agent_id && $user->agent && $property->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut modifier les propriétés de son agence
        if ($user->isAgencyAdmin() && $user->agent && $property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut modifier les propriétés de son entreprise
        if ($user->isCompanyAdmin() && $property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        // Même logique que pour update
        return $this->update($user, $property);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): bool
    {
        // Même logique que pour update
        return $this->update($user, $property);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        // Seul un admin ou super admin peut supprimer définitivement une propriété
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can publish the property.
     */
    public function publish(User $user, Property $property): bool
    {
        // Même logique que pour update
        return $this->update($user, $property);
    }
    
    /**
     * Determine whether the user can feature the property.
     */
    public function feature(User $user, Property $property): bool
    {
        // Seul un admin d'agence, admin d'entreprise, admin ou super admin peut mettre en avant une propriété
        if ($user->isAgencyAdmin() && $user->agent && $property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        if ($user->isCompanyAdmin() && $property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can change the status of the property.
     */
    public function changeStatus(User $user, Property $property): bool
    {
        // Même logique que pour update
        return $this->update($user, $property);
    }
    
    /**
     * Determine whether the user can assign an agent to the property.
     */
    public function assignAgent(User $user, Property $property): bool
    {
        // Un admin d'agence peut assigner un agent de son agence
        if ($user->isAgencyAdmin() && $user->agent && $property->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut assigner un agent de son entreprise
        if ($user->isCompanyAdmin() && $property->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($property->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
}