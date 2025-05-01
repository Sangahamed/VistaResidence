<?php
namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $query = User::query();
        
        // Filtres
        if ($request->has('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }
        
        if ($request->has('company_id')) {
            $query->whereHas('companies', function($q) use ($request) {
                $q->where('companies.id', $request->company_id);
            });
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filtrer selon le rôle de l'utilisateur
        $user = Auth::user();
        
        if ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $query->whereHas('companies', function($q) use ($companyIds) {
                $q->whereIn('companies.id', $companyIds);
            });
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $query->whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            });
        }
        
        // Tri
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        $users = $query->with(['roles', 'companies'])->paginate(15);
        
        // Données pour les filtres
        $roles = Role::all();
        $companies = [];
        
        if ($user->isSuperAdmin()) {
            $companies = Company::all();
        } elseif ($user->isCompanyAdmin()) {
            $companies = $user->companies()->wherePivot('is_admin', true)->get();
        }
        
        return view('users.index', compact('users', 'roles', 'companies'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        
        $roles = Role::all();
        $companies = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $companies = Company::all();
        } elseif ($user->isCompanyAdmin()) {
            $companies = $user->companies()->wherePivot('is_admin', true)->get();
        }
        
        return view('users.create', compact('roles', 'companies'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'account_type' => 'required|in:client,individual,company',
            'role' => 'required|in:user,agent,agency_admin,admin',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit d'assigner ces rôles et entreprises
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            if ($request->has('roles')) {
                foreach ($validated['roles'] as $roleId) {
                    $role = Role::find($roleId);
                    if ($role && ($role->slug === 'super-admin' || $role->slug === 'admin')) {
                        return redirect()->back()
                            ->withErrors(['roles' => 'Vous n\'avez pas le droit d\'assigner ce rôle.'])
                            ->withInput();
                    }
                }
            }
            
            if ($request->has('companies')) {
                $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
                foreach ($validated['companies'] as $companyId) {
                    if (!$companyIds->contains($companyId)) {
                        return redirect()->back()
                            ->withErrors(['companies' => 'Vous n\'avez pas le droit d\'assigner cette entreprise.'])
                            ->withInput();
                    }
                }
            }
        }
        
        $newUser = new User();
        $newUser->name = $validated['name'];
        $newUser->email = $validated['email'];
        $newUser->password = Hash::make($validated['password']);
        $newUser->account_type = $validated['account_type'];
        $newUser->role = $validated['role'];
        $newUser->email_verified_at = now();
        
        // Traiter l'avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($avatar)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'avatars/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $newUser->avatar = $filename;
        }
        
        $newUser->save();
        
        // Assigner les rôles
        if ($request->has('roles')) {
            $newUser->roles()->attach($validated['roles']);
        }
        
        // Assigner les entreprises
        if ($request->has('companies')) {
            foreach ($validated['companies'] as $companyId) {
                $newUser->companies()->attach($companyId, [
                    'job_title' => $request->input('job_title_' . $companyId, null),
                    'is_admin' => $request->boolean('is_admin_' . $companyId),
                ]);
            }
        }
        
        return redirect()->route('users.show', $newUser)
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $user->load(['roles', 'companies', 'agent.agency']);
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $roles = Role::all();
        $companies = [];
        
        $currentUser = Auth::user();
        
        if ($currentUser->isSuperAdmin()) {
            $companies = Company::all();
        } elseif ($currentUser->isCompanyAdmin()) {
            $companies = $currentUser->companies()->wherePivot('is_admin', true)->get();
        }
        
        return view('users.edit', compact('user', 'roles', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'account_type' => 'required|in:client,individual,company',
            'role' => 'required|in:user,agent,agency_admin,admin',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'companies' => 'nullable|array',
            'companies.*' => 'exists:companies,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit d'assigner ces rôles et entreprises
        $currentUser = Auth::user();
        if (!$currentUser->isSuperAdmin()) {
            if ($request->has('roles')) {
                foreach ($validated['roles'] as $roleId) {
                    $role = Role::find($roleId);
                    if ($role && ($role->slug === 'super-admin' || $role->slug === 'admin')) {
                        return redirect()->back()
                            ->withErrors(['roles' => 'Vous n\'avez pas le droit d\'assigner ce rôle.'])
                            ->withInput();
                    }
                }
            }
            
            if ($request->has('companies')) {
                $companyIds = $currentUser->companies()->wherePivot('is_admin', true)->pluck('companies.id');
                foreach ($validated['companies'] as $companyId) {
                    if (!$companyIds->contains($companyId)) {
                        return redirect()->back()
                            ->withErrors(['companies' => 'Vous n\'avez pas le droit d\'assigner cette entreprise.'])
                            ->withInput();
                    }
                }
            }
        }
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->account_type = $validated['account_type'];
        $user->role = $validated['role'];
        
        // Traiter l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar !== 'avatar.png') {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
                Storage::disk('public')->delete('avatars/thumbnails/' . $user->avatar);
            }
            
            $avatar = $request->file('avatar');
            $filename = Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($avatar)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'avatars/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $user->avatar = $filename;
        }
        
        $user->save();
        
        // Mettre à jour les rôles
        if ($request->has('roles')) {
            $user->roles()->sync($validated['roles']);
        } else {
            $user->roles()->detach();
        }
        
        // Mettre à jour les entreprises
        if ($request->has('companies')) {
            $syncData = [];
            
            foreach ($validated['companies'] as $companyId) {
                $syncData[$companyId] = [
                    'job_title' => $request->input('job_title_' . $companyId, null),
                    'is_admin' => $request->boolean('is_admin_' . $companyId),
                ];
            }
            
            $user->companies()->sync($syncData);
        } else {
            $user->companies()->detach();
        }
        
        return redirect()->route('users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Vérifier si l'utilisateur a des relations importantes
        if ($user->agent()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet utilisateur car il est un agent.');
        }
        
        if ($user->properties()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet utilisateur car il possède des propriétés.');
        }
        
        // Supprimer l'avatar
        if ($user->avatar !== 'avatar.png') {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
            Storage::disk('public')->delete('avatars/thumbnails/' . $user->avatar);
        }
        
        // Détacher les relations
        $user->roles()->detach();
        $user->companies()->detach();
        $user->favorites()->detach();
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load(['roles', 'companies', 'agent.agency']);
        
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        // Traiter l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar !== 'avatar.png') {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
                Storage::disk('public')->delete('avatars/thumbnails/' . $user->avatar);
            }
            
            $avatar = $request->file('avatar');
            $filename = Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($avatar)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'avatars/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $user->avatar = $filename;
        }
        
        $user->save();
        
        return redirect()->route('profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'dark_mode' => 'boolean',
            'messenger_color' => 'nullable|string|max:20',
        ]);
        
        $user->dark_mode = $request->boolean('dark_mode');
        $user->messenger_color = $validated['messenger_color'] ?? null;
        $user->save();
        
        // Mettre à jour les préférences de notification
        if ($user->notificationPreferences) {
            $user->notificationPreferences->update([
                'email_notifications' => $request->boolean('email_notifications'),
                'push_notifications' => $request->boolean('push_notifications'),
                'new_property_alerts' => $request->boolean('new_property_alerts'),
                'price_change_alerts' => $request->boolean('price_change_alerts'),
                'status_change_alerts' => $request->boolean('status_change_alerts'),
                'saved_search_alerts' => $request->boolean('saved_search_alerts'),
                'notification_frequency' => json_encode($request->input('notification_frequency', [])),
            ]);
        } else {
            $user->notificationPreferences()->create([
                'email_notifications' => $request->boolean('email_notifications'),
                'push_notifications' => $request->boolean('push_notifications'),
                'new_property_alerts' => $request->boolean('new_property_alerts'),
                'price_change_alerts' => $request->boolean('price_change_alerts'),
                'status_change_alerts' => $request->boolean('status_change_alerts'),
                'saved_search_alerts' => $request->boolean('saved_search_alerts'),
                'notification_frequency' => json_encode($request->input('notification_frequency', [])),
            ]);
        }
        
        return redirect()->route('profile')
            ->with('success', 'Préférences mises à jour avec succès.');
    }
}