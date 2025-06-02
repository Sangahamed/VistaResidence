<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Chatify\Facades\ChatifyMessenger as Chatify;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'picture',
        'address',
        'phone',
        'account_type',
        'role',
        'email_verified_at',
        'verified',
        'status',
        'provider',
        'provider_id',
        'last_login_at',
        'last_login_ip',
        'last_login_agent_user',
        'device_type',
        'device_os',
        'device_browser',
        'device_resolution',
        'device_language',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
        'failed_login_attempts',
        'last_failed_login_at',
    ];

    protected $hidden = ['password', 'remember_token', 'two_factor_code'];

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

    // ------------------ PROFIL ET LOCALISATION ------------------

    public function location()
    {
        return $this->hasOne(UserLocation::class);
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
            'preferences' => config('notification.default_preferences.alerts'),
        ]);
    }

    public function getNotificationPreferencesAttribute()
    {
        return Cache::remember("user.{$this->id}.notif_prefs", 3600, fn() => $this->notificationPreference);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->email))) . "?d=mp&s=200";
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'];
    }

    // ------------------ TYPE DE COMPTE ------------------

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

    // ------------------ RÔLES & PERMISSIONS ------------------

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function assignRole($role)
    {
        $role = is_string($role) ? Role::where('slug', $role)->firstOrFail() : $role;
        return $this->roles()->syncWithoutDetaching($role);
    }

    public function removeRole($role)
    {
        $role = is_string($role) ? Role::where('slug', $role)->firstOrFail() : $role;
        return $this->roles()->detach($role);
    }

    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->hasRole('admin') || $this->permissions()->contains($permissionName);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    // ------------------ ENTREPRISES ------------------

    public function company()
    {
        return $this->hasOne(Company::class, 'owner_id');
    }

    public function hasCompany(): bool
    {
        return $this->company()->exists();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withPivot('job_title', 'is_admin')->withTimestamps();
    }

    public function isCompanyAdmin(): bool
    {
        return $this->companies()->wherePivot('is_admin', true)->exists();
    }

    public function isCompanyMember($companyId): bool
    {
        return $this->companies()->where('companies.id', $companyId)->exists();
    }

    public function isCompanyAdminOf($company): bool
    {
        $id = is_numeric($company) ? $company : $company->id;
        return $this->isAdmin() || $this->companies()->where('companies.id', $id)->wherePivot('is_admin', true)->exists();
    }

    public function ownedCompanies()
    {
        return $this->hasCompany() && $this->company->owner_id === $this->id;
    }

    public function pendingCompany()
    {
        return $this->hasOne(Company::class, 'owner_id')->where('status', 'pending');
    }

    public function activeCompany()
    {
        return $this->hasOne(Company::class, 'owner_id')->where('status', 'approved');
    }

    public function hasPendingCompany()
    {
        return $this->pendingCompany()->exists();
    }

    // ------------------ AGENCES & AGENTS ------------------

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    public function isAgencyAdmin(): bool
    {
        return $this->hasRole('agency_admin');
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent' || $this->hasRole('agent') || $this->isAgencyAdmin();
    }

    public function isAgencyMember($agencyId = null): bool
    {
        return $this->agent && ($agencyId ? $this->agent->agency_id == $agencyId : true);
    }

    public function isAgencyAdminOf($agencyId): bool
    {
        return $this->agent && $this->agent->agency_id === $agencyId && $this->isAgencyAdmin();
    }

    // ------------------ VISITES & PROPRIÉTÉS ------------------

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Property::class, 'user_favorites')->withTimestamps();
    }

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

    public function managedVisits()
    {
        if ($this->isAdmin()) {
            return PropertyVisit::query();
        } elseif ($this->isCompanyAdmin()) {
            $companyIds = $this->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            return PropertyVisit::whereIn('company_id', $companyIds);
        } elseif ($this->isAgencyAdmin()) {
            return PropertyVisit::where('agency_id', $this->agent->agency_id);
        } elseif ($this->isAgent()) {
            return $this->assignedVisits();
        }

        return $this->requestedVisits();
    }

    public function propertyViews()
    {
        return $this->hasMany(PropertyView::class);
    }

    // ------------------ PROJETS, ÉQUIPES, TÂCHES ------------------

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }

    public function isTeamLeader($team): bool
    {
        return $team && $team->leader_id === $this->id;
    }

    public function ledTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withPivot('role')->withTimestamps();
    }

    public function isProjectManager($project): bool
    {
        return $project && $project->manager_id === $this->id;
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    // ------------------ AUTRES ------------------

    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function unreadMessagesCount()
    {
        return Chatify::countUnseenMessages($this->id);
    }

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
}
