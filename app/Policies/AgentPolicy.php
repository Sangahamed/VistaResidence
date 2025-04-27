<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-agents');
    }

    public function view(User $user, Agent $agent)
    {
        return $user->hasPermission('view-agents');
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-agents');
    }

    public function update(User $user, Agent $agent)
    {
        return $user->hasPermission('manage-agents') && 
               ($agent->user_id === $user->id || 
                $user->agencies->contains('id', $agent->agency_id) || 
                $user->hasRole('admin'));
    }

    public function delete(User $user, Agent $agent)
    {
        return $user->hasPermission('manage-agents') && 
               ($agent->user_id === $user->id || 
                $user->agencies->contains('id', $agent->agency_id) || 
                $user->hasRole('admin'));
    }
}
