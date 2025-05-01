<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Property;
use App\Models\Lead;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\PropertyVisit;
use App\Models\Company;
use App\Models\Module;
use App\Policies\UserPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\LeadPolicy;
use App\Policies\AgencyPolicy;
use App\Policies\AgentPolicy;
use App\Policies\PropertyVisitPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ModulePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Property::class => PropertyPolicy::class,
        Lead::class => LeadPolicy::class,
        Agency::class => AgencyPolicy::class,
        Agent::class => AgentPolicy::class,
        PropertyVisit::class => PropertyVisitPolicy::class,
        Company::class => CompanyPolicy::class,
        Module::class => ModulePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Définir un super-admin qui peut tout faire
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        
        // Définir des gates pour les rôles
        Gate::define('admin', function (User $user) {
            return $user->isAdmin() || $user->isSuperAdmin();
        });
        
        Gate::define('agency-admin', function (User $user) {
            return $user->isAgencyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
        });
        
        Gate::define('agent', function (User $user) {
            return $user->isAgent() || $user->isAgencyAdmin() || $user->isAdmin() || $user->isSuperAdmin();
        });
        
        Gate::define('company-admin', function (User $user, Company $company = null) {
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return true;
            }
            
            if ($company) {
                return $user->companies()->where('companies.id', $company->id)->wherePivot('is_admin', true)->exists();
            }
            
            return $user->companies()->wherePivot('is_admin', true)->exists();
        });
    }
}