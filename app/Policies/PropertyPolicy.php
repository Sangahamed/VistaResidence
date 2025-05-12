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
        return $user->hasPermission('view-properties') && ($user->isCompany() || $user->isIndividual());
    }

    public function view(User $user, Property $property)
    {
        return $user->hasPermission('view-properties') && ($user->id === $property->owner_id ||
               ($user->isCompany() && $user->companies->contains('id', $property->company_id)));
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-properties') && ($user->isCompany() || $user->isIndividual());
    }

    public function update(User $user, Property $property)
    {
        if (!$user->hasPermission('manage-properties')) {
            return false;
        }

        // Propriétaire direct
        if ($property->owner_id === $user->id) {
            return true;
        }

        // Utilisateur d'entreprise avec la même société
        if ($user->isCompany() && $user->companies->contains('id', $property->company_id)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Property $property)
    {
        return $this->update($user, $property);
    }
}
