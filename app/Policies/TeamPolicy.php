<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-teams');
    }

    public function view(User $user, Team $team)
    {
        return $user->hasPermission('view-teams') && $user->teams->contains($team);
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-teams');
    }

    public function update(User $user, Team $team)
    {
        return $user->hasPermission('manage-teams') && $user->teams->contains($team);
    }

    public function delete(User $user, Team $team)
    {
        return $user->hasPermission('manage-teams') && $user->teams->contains($team);
    }
}
