<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Un admin d'entreprise, admin ou super admin peut voir la liste des entreprises
        return $user->isCompanyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        // Un utilisateur peut voir une entreprise s'il en est membre
        if ($company->users()->where('users.id', $user->id)->exists()) {
            return true;
        }
        
        // Un admin d'entreprise peut voir les entreprises qu'il administre
        if ($user->isCompanyAdmin()) {
            return $company->users()->where('users.id', $user->id)->wherePivot('is_admin', true)->exists();
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Un admin ou super admin peut créer une entreprise
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        // Un admin d'entreprise peut modifier les entreprises qu'il administre
        if ($user->isCompanyAdmin()) {
            return $company->users()->where('users.id', $user->id)->wherePivot('is_admin', true)->exists();
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        // Seul un admin ou super admin peut supprimer une entreprise
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        // Même logique que pour delete
        return $this->delete($user, $company);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        // Seul un super admin peut supprimer définitivement une entreprise
        return $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can approve the company.
     */
    public function approve(User $user, Company $company): bool
    {
        // Seul un admin ou super admin peut approuver une entreprise
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can reject the company.
     */
    public function reject(User $user, Company $company): bool
    {
        // Seul un admin ou super admin peut rejeter une entreprise
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can add a user to the company.
     */
    public function addUser(User $user, Company $company): bool
    {
        // Un admin d'entreprise peut ajouter un utilisateur aux entreprises qu'il administre
        if ($user->isCompanyAdmin()) {
            return $company->users()->where('users.id', $user->id)->wherePivot('is_admin', true)->exists();
        }
        
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can remove a user from the company.
     */
    public function removeUser(User $user, Company $company): bool
    {
        // Même logique que pour addUser
        return $this->addUser($user, $company);
    }
    
    /**
     * Determine whether the user can add a module to the company.
     */
    public function addModule(User $user, Company $company): bool
    {
        // Seul un admin ou super admin peut ajouter un module à une entreprise
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can remove a module from the company.
     */
    public function removeModule(User $user, Company $company): bool
    {
        // Même logique que pour addModule
        return $this->addModule($user, $company);
    }
}