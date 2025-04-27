<?php

namespace App\Policies;

use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyVisitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PropertyVisit $visit)
    {
        // L'utilisateur peut voir la visite s'il est le visiteur, l'agent assigné, 
        // l'agent de la propriété ou un administrateur
        return $user->id === $visit->visitor_id || 
               $user->id === $visit->agent_id || 
               $user->id === $visit->property->agent_id ||
               $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PropertyVisit $visit)
    {
        // Seul l'agent assigné à la visite ou un administrateur peut la mettre à jour
        return $user->id === $visit->agent_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, PropertyVisit $visit)
    {
        // Le visiteur, l'agent assigné ou un administrateur peut annuler la visite
        return $user->id === $visit->visitor_id || 
               $user->id === $visit->agent_id || 
               $user->hasRole('admin');
    }
}