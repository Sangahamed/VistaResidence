<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);
        
        $query = Company::query();
        
        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }
        
        // Filtrer selon le rôle de l'utilisateur
        $user = Auth::user();
        
        if (!$user->isSuperAdmin()) {
            $query->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        // Tri
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        $companies = $query->with(['owner'])->paginate(15);
        
        // Données pour les filtres
        $statuses = [
            'pending' => 'En attente',
            'approved' => 'Approuvée',
            'rejected' => 'Rejetée'
        ];
        
        return view('companies.index', compact('companies', 'statuses'));
    }

    public function create()
    {
        $this->authorize('create', Company::class);
        
        $users = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $users = User::all();
        } else {
            $users = [$user];
        }
        
        return view('companies.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Company::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'owner_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit de créer une entreprise pour cet utilisateur
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $validated['owner_id'] !== $user->id) {
            return redirect()->back()
                ->withErrors(['owner_id' => 'Vous n\'avez pas le droit de créer une entreprise pour cet utilisateur.'])
                ->withInput();
        }
        
        $company = new Company();
        $company->fill($validated);
        
        // Générer le slug
        $company->slug = Str::slug($validated['name']);
        
        // Traiter le logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('companies/logos', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($logo)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'companies/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $company->logo = $path;
        }
        
        // Si l'utilisateur est un super admin, il peut approuver directement
        if ($user->isSuperAdmin() && $validated['status'] === 'approved') {
            $company->approved_at = now();
        }
        
        $company->save();
        
        // Ajouter le propriétaire comme administrateur
        $company->users()->attach($validated['owner_id'], [
            'job_title' => 'Propriétaire',
            'is_admin' => true,
        ]);
        
        // Ajouter l'utilisateur courant comme administrateur s'il n'est pas le propriétaire
        if ($user->id !== $validated['owner_id']) {
            $company->users()->attach($user->id, [
                'job_title' => 'Administrateur',
                'is_admin' => true,
            ]);
        }
        
        return redirect()->route('companies.show', $company)
            ->with('success', 'Entreprise créée avec succès.');
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);
        
        $company->load(['owner', 'users', 'agencies', 'modules']);
        
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        
        $users = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $users = User::all();
        } else {
            $users = [$user];
        }
        
        return view('companies.edit', compact('company', 'users'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'owner_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit de modifier cette entreprise
        $user = Auth::user();
        if (!$user->isSuperAdmin() && !$company->users()->where('users.id', $user->id)->wherePivot('is_admin', true)->exists()) {
            return redirect()->back()
                ->withErrors(['owner_id' => 'Vous n\'avez pas le droit de modifier cette entreprise.'])
                ->withInput();
        }
        
        $company->fill($validated);
        
        // Mettre à jour le slug si le nom a changé
        if ($company->isDirty('name')) {
            $company->slug = Str::slug($validated['name']);
        }
        
        // Traiter le logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
                
                // Supprimer la miniature
                $oldFilename = basename($company->logo);
                $oldThumbnailPath = 'companies/thumbnails/' . $oldFilename;
                Storage::disk('public')->delete($oldThumbnailPath);
            }
            
            $logo = $request->file('logo');
            $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('companies/logos', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($logo)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'companies/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $company->logo = $path;
        }
        
        // Si l'utilisateur est un super admin, il peut changer le statut
        if ($user->isSuperAdmin()) {
            // Si le statut passe à "approved", mettre à jour la date d'approbation
            if ($company->status !== 'approved' && $validated['status'] === 'approved') {
                $company->approved_at = now();
            }
        }
        
        $company->save();
        
        // Mettre à jour le propriétaire si nécessaire
        if ($company->owner_id !== $validated['owner_id']) {
            // Vérifier si l'ancien propriétaire est un administrateur
            $oldOwnerIsAdmin = $company->users()->where('users.id', $company->owner_id)->wherePivot('is_admin', true)->exists();
            
            // Mettre à jour le propriétaire
            $company->owner_id = $validated['owner_id'];
            $company->save();
            
            // Ajouter le nouveau propriétaire comme administrateur s'il n'est pas déjà un utilisateur
            if (!$company->users()->where('users.id', $validated['owner_id'])->exists()) {
                $company->users()->attach($validated['owner_id'], [
                    'job_title' => 'Propriétaire',
                    'is_admin' => true,
                ]);
            } else {
                // Mettre à jour le rôle du nouveau propriétaire
                $company->users()->updateExistingPivot($validated['owner_id'], [
                    'job_title' => 'Propriétaire',
                    'is_admin' => true,
                ]);
            }
        }
        
        return redirect()->route('companies.show', $company)
            ->with('success', 'Entreprise mise à jour avec succès.');
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);
        
        // Vérifier si l'entreprise a des agences
        if ($company->agencies()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette entreprise car elle a des agences associées.');
        }
        
        // Vérifier si l'entreprise a des propriétés
        if ($company->properties()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette entreprise car elle a des propriétés associées.');
        }
        
        // Supprimer le logo
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
            
            // Supprimer la miniature
            $filename = basename($company->logo);
            $thumbnailPath = 'companies/thumbnails/' . $filename;
            Storage::disk('public')->delete($thumbnailPath);
        }
        
        // Détacher les relations
        $company->users()->detach();
        $company->modules()->detach();
        
        $company->delete();
        
        return redirect()->route('companies.index')
            ->with('success', 'Entreprise supprimée avec succès.');
    }

    public function users(Company $company)
    {
        $this->authorize('view', $company);
        
        $company->load(['users']);
        
        return view('companies.users', compact('company'));
    }

    public function agencies(Company $company)
    {
        $this->authorize('view', $company);
        
        $agencies = $company->agencies()->paginate(15);
        
        return view('companies.agencies', compact('company', 'agencies'));
    }

    public function modules(Company $company)
    {
        $this->authorize('view', $company);
        
        $company->load(['modules']);
        
        // Modules disponibles
        $availableModules = Module::whereDoesntHave('companies', function($q) use ($company) {
            $q->where('companies.id', $company->id);
        })->get();
        
        return view('companies.modules', compact('company', 'availableModules'));
    }

    public function addUser(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_title' => 'nullable|string|max:255',
            'is_admin' => 'boolean',
            'role_id' => 'nullable|exists:roles,id',
        ]);
        
        // Vérifier si l'utilisateur est déjà membre de cette entreprise
        if ($company->users()->where('users.id', $validated['user_id'])->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur est déjà membre de cette entreprise.'])
                ->withInput();
        }
        
        // Ajouter l'utilisateur
        $company->users()->attach($validated['user_id'], [
            'job_title' => $validated['job_title'] ?? null,
            'is_admin' => $request->boolean('is_admin'),
            'role_id' => $validated['role_id'] ?? null,
        ]);
        
        return redirect()->route('companies.users', $company)
            ->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function updateUser(Request $request, Company $company, $userId)
    {
        $this->authorize('update', $company);
        
        $validated = $request->validate([
            'job_title' => 'nullable|string|max:255',
            'is_admin' => 'boolean',
            'role_id' => 'nullable|exists:roles,id',
        ]);
        
        // Vérifier si l'utilisateur est membre de cette entreprise
        if (!$company->users()->where('users.id', $userId)->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur n\'est pas membre de cette entreprise.'])
                ->withInput();
        }
        
        // Mettre à jour l'utilisateur
        $company->users()->updateExistingPivot($userId, [
            'job_title' => $validated['job_title'] ?? null,
            'is_admin' => $request->boolean('is_admin'),
            'role_id' => $validated['role_id'] ?? null,
        ]);
        
        return redirect()->route('companies.users', $company)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function removeUser(Company $company, $userId)
    {
        $this->authorize('update', $company);
        
        // Vérifier si l'utilisateur est membre de cette entreprise
        if (!$company->users()->where('users.id', $userId)->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur n\'est pas membre de cette entreprise.'])
                ->withInput();
        }
        
        // Vérifier si l'utilisateur est le propriétaire
        if ($company->owner_id == $userId) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Vous ne pouvez pas retirer le propriétaire de l\'entreprise.'])
                ->withInput();
        }
        
        // Retirer l'utilisateur
        $company->users()->detach($userId);
        
        return redirect()->route('companies.users', $company)
            ->with('success', 'Utilisateur retiré avec succès.');
    }

    public function addModule(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'is_enabled' => 'boolean',
            'settings' => 'nullable|array',
            'expires_at' => 'nullable|date|after:today',
        ]);
        
        // Vérifier si le module est déjà associé à cette entreprise
        if ($company->modules()->where('modules.id', $validated['module_id'])->exists()) {
            return redirect()->back()
                ->withErrors(['module_id' => 'Ce module est déjà associé à cette entreprise.'])
                ->withInput();
        }
        
        // Ajouter le module
        $company->modules()->attach($validated['module_id'], [
            'is_enabled' => $request->boolean('is_enabled'),
            'settings' => json_encode($validated['settings'] ?? []),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);
        
        return redirect()->route('companies.modules', $company)
            ->with('success', 'Module ajouté avec succès.');
    }

    public function updateModule(Request $request, Company $company, $moduleId)
    {
        $this->authorize('update', $company);
        
        $validated = $request->validate([
            'is_enabled' => 'boolean',
            'settings' => 'nullable|array',
            'expires_at' => 'nullable|date|after:today',
        ]);
        
        // Vérifier si le module est associé à cette entreprise
        if (!$company->modules()->where('modules.id', $moduleId)->exists()) {
            return redirect()->back()
                ->withErrors(['module_id' => 'Ce module n\'est pas associé à cette entreprise.'])
                ->withInput();
        }
        
        // Mettre à jour le module
        $company->modules()->updateExistingPivot($moduleId, [
            'is_enabled' => $request->boolean('is_enabled'),
            'settings' => json_encode($validated['settings'] ?? []),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);
        
        return redirect()->route('companies.modules', $company)
            ->with('success', 'Module mis à jour avec succès.');
    }

    public function removeModule(Company $company, $moduleId)
    {
        $this->authorize('update', $company);
        
        // Vérifier si le module est associé à cette entreprise
        if (!$company->modules()->where('modules.id', $moduleId)->exists()) {
            return redirect()->back()
                ->withErrors(['module_id' => 'Ce module n\'est pas associé à cette entreprise.'])
                ->withInput();
        }
        
        // Retirer le module
        $company->modules()->detach($moduleId);
        
        return redirect()->route('companies.modules', $company)
            ->with('success', 'Module retiré avec succès.');
    }

    public function approve(Company $company)
    {
        $this->authorize('approve', $company);
        
        if ($company->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cette entreprise n\'est pas en attente d\'approbation.');
        }
        
        $company->status = 'approved';
        $company->approved_at = now();
        $company->save();
        
        return redirect()->route('companies.show', $company)
            ->with('success', 'Entreprise approuvée avec succès.');
    }

    public function reject(Request $request, Company $company)
    {
        $this->authorize('reject', $company);
        
        if ($company->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cette entreprise n\'est pas en attente d\'approbation.');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);
        
        $company->status = 'rejected';
        $company->rejection_reason = $validated['rejection_reason'];
        $company->save();
        
        return redirect()->route('companies.show', $company)
            ->with('success', 'Entreprise rejetée avec succès.');
    }
}
