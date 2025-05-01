<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Un agent, admin d'agence, admin d'entreprise, admin ou super admin peut voir la liste des leads
        return $user->isAgent() || $user->isAgencyAdmin() || $user->isCompanyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lead $lead): bool
    {
        // L'agent assigné au lead peut le voir
        if ($lead->agent_id && $user->agent && $lead->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut voir les leads de son agence
        if ($user->isAgencyAdmin() && $user->agent && $lead->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut voir les leads de son entreprise
        if ($user->isCompanyAdmin() && $lead->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($lead->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Un agent, admin d'agence, admin d'entreprise, admin ou super admin peut créer un lead
        return $user->isAgent() || $user->isAgencyAdmin() || $user->isCompanyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lead $lead): bool
    {
        // L'agent assigné au lead peut le modifier
        if ($lead->agent_id && $user->agent && $lead->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut modifier les leads de son agence
        if ($user->isAgencyAdmin() && $user->agent && $lead->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut modifier les leads de son entreprise
        if ($user->isCompanyAdmin() && $lead->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($lead->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lead $lead): bool
    {
        // Un admin d'agence peut supprimer les leads de son agence
        if ($user->isAgencyAdmin() && $user->agent && $lead->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut supprimer les leads de son entreprise
        if ($user->isCompanyAdmin() && $lead->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($lead->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lead $lead): bool
    {
        // Même logique que pour delete
        return $this->delete($user, $lead);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lead $lead): bool
    {
        // Seul un admin ou super admin peut supprimer définitivement un lead
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can assign an agent to the lead.
     */
    public function assign(User $user, Lead $lead): bool
    {
        // Un admin d'agence peut assigner un agent de son agence
        if ($user->isAgencyAdmin() && $user->agent && $lead->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut assigner un agent de son entreprise
        if ($user->isCompanyAdmin() && $lead->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($lead->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can change the status of the lead.
     */
    public function changeStatus(User $user, Lead $lead): bool
    {
        // L'agent assigné au lead peut changer son statut
        if ($lead->agent_id && $user->agent && $lead->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut changer le statut des leads de son agence
        if ($user->isAgencyAdmin() && $user->agent && $lead->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut changer le statut des leads de son entreprise
        if ($user->isCompanyAdmin() && $lead->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($lead->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can add an activity to the lead.
     */
    public function addActivity(User $user, Lead $lead): bool
    {
        // L'agent assigné au lead peut ajouter une activité
        if ($lead->agent_id && $user->agent && $lead->agent_id === $user->agent->id) {
            return true;
        }
        
        // Un admin d'agence peut ajouter une activité aux leads de son agence
        if ($user->isAgencyAdmin() && $user->agent && $lead->agency_id === $user->agent->agency_id) {
            return true;
        }
        
        // Un admin d'entreprise peut ajouter une activité aux leads de son entreprise
        if ($user->isCompanyAdmin() && $lead->company_id) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $companyIds->contains($lead->company_id);
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
}