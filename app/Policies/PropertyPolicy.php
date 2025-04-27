<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-properties');
    }

    public function view(User $user, Property $property)
    {
        return $user->hasPermission('view-properties');
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-properties');
    }

    public function update(User $user, Property $property)
    {
        return $user->hasPermission('manage-properties') && 
               ($property->user_id === $user->id || $user->hasRole('admin'));
    }

    public function delete(User $user, Property $property)
    {
        return $user->hasPermission('manage-properties') && 
               ($property->user_id === $user->id || $user->hasRole('admin'));
    }
}
