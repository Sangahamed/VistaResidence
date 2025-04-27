<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-companies');
    }

    public function view(User $user, Company $company)
    {
        return $user->hasPermission('view-companies') && $user->companies->contains($company);
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage-companies');
    }

    public function update(User $user, Company $company)
    {
        return $user->hasPermission('manage-companies') && $user->companies->contains($company);
    }

    public function delete(User $user, Company $company)
    {
        return $user->hasPermission('manage-companies') && $user->companies->contains($company);
    }
}
