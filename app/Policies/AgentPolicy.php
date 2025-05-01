<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur authentifié peut voir la liste des agents
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agent $agent): bool
    {
        // Tout utilisateur authentifié peut voir un agent
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Un admin d'agence, admin d'entreprise, admin ou super admin peut créer un agent
        return $user->isAgencyAdmin() || $user->isCompanyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agent $agent): bool
    {
        // L'agent lui-même peut modifier son profil
        if ($user->agent && $agent->id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut modifier les agents de son agence
        if ($user->isAgencyAdmin() && $user->agent && $agent->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut modifier les agents des agences de son entreprise
        if ($user->isCompanyAdmin() && $agent->agency && $agent->agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agent->agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agent $agent): bool
    {
        // Un admin d'agence peut supprimer les agents de son agence
        if ($user->isAgencyAdmin() && $user->agent && $agent->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut supprimer les agents des agences de son entreprise
        if ($user->isCompanyAdmin() && $agent->agency && $agent->agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agent->agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agent $agent): bool
    {
        // Même logique que pour delete
        return $this->delete($user, $agent);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agent $agent): bool
    {
        // Seul un admin ou super admin peut supprimer définitivement un agent
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can change the status of the agent.
     */
    public function changeStatus(User $user, Agent $agent): bool
    {
        // Un admin d'agence peut changer le statut des agents de son agence
        if ($user->isAgencyAdmin() && $user->agent && $agent->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut changer le statut des agents des agences de son entreprise
        if ($user->isCompanyAdmin() && $agent->agency && $agent->agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agent->agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can assign properties to the agent.
     */
    public function assignProperties(User $user, Agent $agent): bool
    {
        // Un admin d'agence peut assigner des propriétés aux agents de son agence
        if ($user->isAgencyAdmin() && $user->agent && $agent->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut assigner des propriétés aux agents des agences de son entreprise
        if ($user->isCompanyAdmin() && $agent->agency && $agent->agency->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($agent->agency->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can assign leads to the agent.
     */
    public function assignLeads(User $user, Agent $agent): bool
    {
        // Même logique que pour assignProperties
        return $this->assignProperties($user, $agent);
    }
}