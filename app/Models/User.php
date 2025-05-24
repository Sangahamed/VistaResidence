<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Chatify\Facades\ChatifyMessenger as Chatify;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Attributs remplissables en masse (Mass Assignment).
     * Permet d'éviter les erreurs de sécurité en définissant les champs modifiables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'picture', 'address', 'phone', 
        'account_type', 'role', 'email_verified_at', 'verified', 'status', 
        'provider', 'provider_id', 'last_login_at', 'last_login_ip', 'last_login_agent_user', 
        'device_type', 'device_os', 'device_browser', 'device_resolution', 'device_language', 
        'two_factor_enabled', 'two_factor_code', 'two_factor_expires_at', 'failed_login_attempts', 
        'last_failed_login_at',
    ];

    /**
     * Attributs masqués lors de la sérialisation (JSON, API, etc.).
     * Cela permet d'éviter de divulguer des informations sensibles.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'two_factor_code'];

    /**
     * Définition des types des attributs pour une conversion automatique.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_failed_login_at' => 'datetime',
        ];
    }


    public function isClient()
    {
        return $this->account_type === 'client';
    }

    public function isIndividual()
    {
        return $this->account_type === 'individual';
    }

    public function isCompany()
    {
        return $this->account_type === 'company';
    }
    
    public function company()
    {
        return $this->hasOne(Company::class, 'owner_id');
    }

    // ---------------------- Gestion du profil utilisateur ----------------------

    /**
     * Retourne l'URL de la photo de profil de l'utilisateur.
     * Si un avatar est présent, il est utilisé, sinon Gravatar est généré.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) 
            : "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->email))) . "?d=mp&s=200";
    }

    /**
     * Retourne le nom de l'utilisateur.
     */
    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    /**
     * Compte le nombre de messages non lus de l'utilisateur.
     */
    public function unreadMessagesCount()
    {
        return Chatify::countUnseenMessages($this->id);
    }

    // ---------------------- Gestion des rôles et permissions ----------------------

    /**
     * Relation avec les rôles (Un utilisateur peut avoir plusieurs rôles).
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Vérifie si l'utilisateur possède un rôle spécifique.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Vérifie si l'utilisateur possède au moins un des rôles donnés.
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    /**
     * Vérifie si l'utilisateur possède tous les rôles donnés.
     */
    public function hasAllRoles(array $roleNames): bool
    {
        return count(array_intersect($roleNames, $this->roles()->pluck('name')->toArray())) === count($roleNames);
    }


    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching($role);
        return $this;
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->detach($role);
        return $this;
    }

    public function syncRoles($roles)
    {
        if (is_array($roles)) {
            $roles = Role::whereIn('slug', $roles)->get();
        }

        $this->roles()->sync($roles);
        return $this;
    }

    /**
     * Retourne toutes les permissions de l'utilisateur (héritées via les rôles).
     */
    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    /**
     * Vérifie si l'utilisateur possède une permission spécifique.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->hasRole('admin') || $this->permissions()->contains($permissionName);
    }

    /**
     * Vérifie si l'utilisateur possède au moins une des permissions données.
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        return $this->hasRole('admin') || collect($permissionNames)->contains(fn($perm) => $this->permissions()->contains($perm));
    }

    // ---------------------- Gestion des entreprises et propriétés ----------------------

    /**
     * Relation avec une entreprise spécifique.
     */

        public function pendingCompany()
    {
        return $this->hasOne(Company::class, 'owner_id')->where('status', 'pending');
    }

    public function hasPendingCompany()
    {
        return $this->pendingCompany()->exists();
    }

    public function activeCompany()
    {
        return $this->hasOne(Company::class, 'owner_id')->where('status', 'approved');
    }

     
     // Ajoutez ces méthodes
        public function isAgencyMember($agencyId = null)
        {
            if (!$this->agent) return false;
            
            return $agencyId 
                ? $this->agent->agency_id == $agencyId
                : $this->agent->agency_id !== null;
        }

        public function managedVisits()
        {
            if ($this->isSuperAdmin()) {
                return PropertyVisit::query();
            } elseif ($this->isCompanyAdmin()) {
                $companyIds = $this->companies()->wherePivot('is_admin', true)->pluck('companies.id');
                return PropertyVisit::whereIn('company_id', $companyIds);
            } elseif ($this->isAgencyAdmin()) {
                return PropertyVisit::where('agency_id', $this->agent->agency_id);
            } elseif ($this->isAgent()) {
                return $this->assignedVisits();
            } else {
                return $this->requestedVisits();
            }
        }
    

   public function hasCompany()
    {
        return (bool) $this->company()->exists();
    }


    /**
     * Relation avec plusieurs entreprises (Un utilisateur peut être membre de plusieurs entreprises).
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withPivot('job_title', 'is_admin')->withTimestamps();
    }

    /**
     * Vérifie si l'utilisateur est administrateur d'une entreprise donnée.
     */
   public function isCompanyAdmin(): bool
    {
        // Vérifie si l'utilisateur est admin d'au moins une company
        return $this->companies()->wherePivot('is_admin', true)->exists();
    }

    /**
     * Retourne les entreprises possédées par l'utilisateur.
     */
    public function ownedCompanies()
    {
        return $this->hasCompany() && $this->company->owner_id === $this->id;
    }

    /**
     * Relation avec les propriétés possédées par l'utilisateur.
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    /**
     * Relation avec les propriétés favorites de l'utilisateur.
     */
    public function favorites()
    {
        return $this->belongsToMany(Property::class, 'user_favorites')->withTimestamps();
    }

    public function favoritedProperties()
    {
        return $this->belongsToMany(Property::class, 'favorites');
    }

    // ---------------------- Gestion des équipes et projets ----------------------

    /**
     * Relation avec les équipes auxquelles appartient l'utilisateur.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Vérifie si l'utilisateur est le leader d'une équipe donnée.
     */
    public function isTeamLeader($team): bool
    {
        return $team && $team->leader_id === $this->id;
    }

    public function leadingTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    /**
     * Relation avec les projets auxquels l'utilisateur participe.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Vérifie si l'utilisateur est manager d'un projet donné.
     */
    public function isProjectManager($project): bool
    {
        return $project && $project->manager_id === $this->id;
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }



    ### taches 

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    ### visites

    public function requestedVisits()
    {
        return $this->hasMany(PropertyVisit::class, 'visitor_id');
    }

    public function assignedVisits()
    {
        return $this->hasMany(PropertyVisit::class, 'agent_id');
    }

    public function upcomingRequestedVisits()
    {
        return $this->requestedVisits()->upcoming();
    }

    public function upcomingAssignedVisits()
    {
        return $this->assignedVisits()->upcoming();
    }

    ### Invitation

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user')
            ->withPivot('used_at', 'amount')
            ->withTimestamps();
    }


    public function agent()
    {
        return $this->hasOne(Agent::class);
    }


    public function ledTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }


    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function propertyViews()
    {
        return $this->hasMany(PropertyView::class);
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function notificationPreference()
{
    return $this->hasOne(NotificationPreference::class)->withDefault([
        'email_enabled' => true,
        'push_enabled' => true,
        'sms_enabled' => false,
        'frequency' => 'instant',
        'preferences' => config('notification.default_preferences.alerts')
    ]);
}

    public function getNotificationPreferencesAttribute()
    {
        return Cache::remember("user.{$this->id}.notif_prefs", 3600, function () {
            return $this->notificationPreference;
        });
    }

    

    public function visits()
    {
        return $this->hasMany(PropertyVisit::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionBid::class);
    }

    public function winningAuctions()
    {
        return $this->hasMany(PropertyAuction::class, 'current_bidder_id');
    }



    public function isAgencyAdmin()
    {
        return $this->hasRole('agency_admin');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }



    /**
     * Get the notification preferences of the user.
     */
    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }


    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->hasRole('admin') || $this->isSuperAdmin();
    }

    /**
     * Check if the user is an agent.
     */
    public function isAgent()
    {
        return $this->role === 'agent' || $this->hasRole('agent') || $this->isAgencyAdmin();
    }


    /**
     * Check if the user is a member of a specific company.
     */
    public function isCompanyMember($companyId)
    {
        return $this->companies()->where('companies.id', $companyId)->exists();
    }

    /**
     * Check if the user is an admin of a specific company.
     */
    public function isCompanyAdminOf($company): bool
{
    if ($this->isAdmin()) {
        return true;
    }
    
    if (is_numeric($company)) {
        return $this->companies()->where('companies.id', $company)
                      ->wherePivot('is_admin', true)
                      ->exists();
    }
    
    return $this->companies()->where('companies.id', $company->id)
                  ->wherePivot('is_admin', true)
                  ->exists();
}

   

    /**
     * Check if the user is an admin of a specific agency.
     */
    public function isAgencyAdminOf($agencyId)
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        if (!$this->agent) {
            return false;
        }
        
        return $this->agent->agency_id === $agencyId && $this->isAgencyAdmin();
    }
}
