<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin() || $user->isAgencyAdmin() || $user->isCompanyAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Un utilisateur peut voir son propre profil
        if ($user->id === $model->id) {
            return true;
        }
        
        // Un admin d'agence peut voir les utilisateurs de son agence
        if ($user->isAgencyAdmin() && $model->agent && $user->agent) {
            return $model->agent->agency_id === $user->agent->agency_id;
        }
        
        // Un admin d'entreprise peut voir les utilisateurs de son entreprise
        if ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $model->companies()->whereIn('companies.id', $companyIds)->exists();
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin() || $user->isAgencyAdmin() || $user->isCompanyAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Un utilisateur peut modifier son propre profil
        if ($user->id === $model->id) {
            return true;
        }
        
        // Un admin d'agence peut modifier les utilisateurs de son agence
        if ($user->isAgencyAdmin() && $model->agent && $user->agent) {
            return $model->agent->agency_id === $user->agent->agency_id;
        }
        
        // Un admin d'entreprise peut modifier les utilisateurs de son entreprise
        if ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $model->companies()->whereIn('companies.id', $companyIds)->exists();
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Un utilisateur ne peut pas se supprimer lui-même
        if ($user->id === $model->id) {
            return false;
        }
        
        // Un admin d'agence peut supprimer les utilisateurs de son agence
        if ($user->isAgencyAdmin() && $model->agent && $user->agent) {
            return $model->agent->agency_id === $user->agent->agency_id;
        }
        
        // Un admin d'entreprise peut supprimer les utilisateurs de son entreprise
        if ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return $model->companies()->whereIn('companies.id', $companyIds)->exists();
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Même logique que pour delete
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Seul un super admin peut supprimer définitivement un utilisateur
        return $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can change the role of the model.
     */
    public function changeRole(User $user, User $model): bool
    {
        // Seul un admin ou super admin peut changer le rôle d'un utilisateur
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can impersonate the model.
     */
    public function impersonate(User $user, User $model): bool
    {
        // Seul un super admin peut se faire passer pour un autre utilisateur
        return $user->isSuperAdmin();
    }
}