<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Seul un admin ou super admin peut voir la liste des modules
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Module $module): bool
    {
        // Seul un admin ou super admin peut voir un module
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul un super admin peut créer un module
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Module $module): bool
    {
        // Seul un super admin peut modifier un module
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Module $module): bool
    {
        // Seul un super admin peut supprimer un module
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Module $module): bool
    {
        // Seul un super admin peut restaurer un module
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Module $module): bool
    {
        // Seul un super admin peut supprimer définitivement un module
        return $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can enable the module.
     */
    public function enable(User $user, Module $module): bool
    {
        // Seul un admin ou super admin peut activer un module
        return $user->isAdmin() || $user->isSuperAdmin();
    }
    
    /**
     * Determine whether the user can disable the module.
     */
    public function disable(User $user, Module $module): bool
    {
        // Seul un admin ou super admin peut désactiver un module
        return $user->isAdmin() || $user->isSuperAdmin();
    }
}