<?php

namespace App\Policies;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgencyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur authentifié peut voir la liste des agences
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agency $agency): bool
    {
        // Tout utilisateur authentifié peut voir une agence
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Un admin d'entreprise, admin ou super admin peut créer une agence
        return $user->isCompanyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agency $agency): bool
    {
        // Un admin d'agence peut modifier son agence
        if ($user->isAgencyAdmin() && $user->agent && $agency->id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut modifier les agences de son entreprise
        if ($user->isCompanyAdmin() && $agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agency $agency): bool
    {
        // Un admin d'entreprise peut supprimer les agences de son entreprise
        if ($user->isCompanyAdmin() && $agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agency $agency): bool
    {
        // Même logique que pour delete
        return $this->delete($user, $agency);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agency $agency): bool
    {
        // Seul un admin ou super admin peut supprimer définitivement une agence
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can add an agent to the agency.
     */
    public function addAgent(User $user, Agency $agency): bool
    {
        // Un admin d'agence peut ajouter un agent à son agence
        if ($user->isAgencyAdmin() && $user->agent && $agency->id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut ajouter un agent aux agences de son entreprise
        if ($user->isCompanyAdmin() && $agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can remove an agent from the agency.
     */
    public function removeAgent(User $user, Agency $agency): bool
    {
        // Même logique que pour addAgent
        return $this->addAgent($user, $agency);
    }
    
    /**
     * Determine whether the user can change the status of the agency.
     */
    public function changeStatus(User $user, Agency $agency): bool
    {
        // Un admin d'entreprise peut changer le statut des agences de son entreprise
        if ($user->isCompanyAdmin() && $agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
}