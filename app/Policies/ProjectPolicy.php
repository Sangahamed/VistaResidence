<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-projects');
    }

    public function view(User $user, Project $project)
    {
        return $user->hasPermission('view-projects') && $user->projects->contains($project);
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-projects');
    }

    public function update(User $user, Project $project)
    {
        return $user->hasPermission('manage-projects') && $user->projects->contains($project);
    }

    public function delete(User $user, Project $project)
    {
        return $user->hasPermission('manage-projects') && $user->projects->contains($project);
    }
}
