<?php

namespace App\Policies;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgencyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-agencies');
    }

    public function view(User $user, Agency $agency)
    {
        return $user->hasPermission('view-agencies');
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-agencies');
    }

    public function update(User $user, Agency $agency)
    {
        return $user->hasPermission('manage-agencies') && 
               ($agency->owner_id === $user->id || $user->hasRole('admin'));
    }

    public function delete(User $user, Agency $agency)
    {
        return $user->hasPermission('manage-agencies') && 
               ($agency->owner_id === $user->id || $user->hasRole('admin'));
    }
}
