<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Company;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AgencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Agency::class);
        
        $query = Agency::query();
        
        // Filtres
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Filtrer selon le rôle de l'utilisateur
        $user = Auth::user();
        
        if ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $query->whereIn('company_id', $companyIds);
        }
        
        // Tri
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        $agencies = $query->with(['company', 'owner'])->paginate(15);
        
        // Données pour les filtres
        $companies = [];
        
        if ($user->isSuperAdmin()) {
            $companies = Company::all();
        } elseif ($user->isCompanyAdmin()) {
            $companies = $user->companies()->wherePivot('is_admin', true)->get();
        }
        
        return view('agencies.index', compact('agencies', 'companies'));
    }

    public function create()
    {
        $this->authorize('create', Agency::class);
        
        $companies = [];
        $users = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $companies = Company::all();
            $users = User::all();
        } elseif ($user->isCompanyAdmin()) {
            $companies = $user->companies()->wherePivot('is_admin', true)->get();
            $companyIds = $companies->pluck('id');
            $users = User::whereHas('companies', function($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            })->get();
        }
        
        return view('agencies.create', compact('companies', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Agency::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'company_id' => 'required|exists:companies,id',
            'owner_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit de créer une agence pour cette entreprise
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            if (!$companyIds->contains($validated['company_id'])) {
                return redirect()->back()
                    ->withErrors(['company_id' => 'Vous n\'avez pas le droit de créer une agence pour cette entreprise.'])
                    ->withInput();
            }
        }
        
        $agency = new Agency();
        $agency->fill($validated);
        
        // Générer le slug
        $agency->slug = Str::slug($validated['name']);
        
        // Traiter le logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('agencies/logos', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($logo)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'agencies/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $agency->logo = $path;
        }
        
        $agency->save();
        
        return redirect()->route('agencies.show', $agency)
            ->with('success', 'Agence créée avec succès.');
    }

    public function show(Agency $agency)
    {
        $this->authorize('view', $agency);
        
        $agency->load(['company', 'owner', 'agents.user', 'properties']);
        
        return view('agencies.show', compact('agency'));
    }

    public function edit(Agency $agency)
    {
        $this->authorize('update', $agency);
        
        $companies = [];
        $users = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $companies = Company::all();
            $users = User::all();
        } elseif ($user->isCompanyAdmin()) {
            $companies = $user->companies()->wherePivot('is_admin', true)->get();
            $companyIds = $companies->pluck('id');
            $users = User::whereHas('companies', function($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            })->get();
        }
        
        return view('agencies.edit', compact('agency', 'companies', 'users'));
    }

    public function update(Request $request, Agency $agency)
    {
        $this->authorize('update', $agency);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'company_id' => 'required|exists:companies,id',
            'owner_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit de modifier cette agence
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $agency->company_id !== $validated['company_id']) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            if (!$companyIds->contains($validated['company_id'])) {
                return redirect()->back()
                    ->withErrors(['company_id' => 'Vous n\'avez pas le droit de modifier l\'entreprise de cette agence.'])
                    ->withInput();
            }
        }
        
        $agency->fill($validated);
        
        // Mettre à jour le slug si le nom a changé
        if ($agency->isDirty('name')) {
            $agency->slug = Str::slug($validated['name']);
        }
        
        // Traiter le logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
                
                // Supprimer la miniature
                $oldFilename = basename($agency->logo);
                $oldThumbnailPath = 'agencies/thumbnails/' . $oldFilename;
                Storage::disk('public')->delete($oldThumbnailPath);
            }
            
            $logo = $request->file('logo');
            $filename = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('agencies/logos', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($logo)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'agencies/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $agency->logo = $path;
        }
        
        $agency->save();
        
        return redirect()->route('agencies.show', $agency)
            ->with('success', 'Agence mise à jour avec succès.');
    }

    public function destroy(Agency $agency)
    {
        $this->authorize('delete', $agency);
        
        // Vérifier si l'agence a des agents
        if ($agency->agents()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette agence car elle a des agents associés.');
        }
        
        // Vérifier si l'agence a des propriétés
        if ($agency->properties()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette agence car elle a des propriétés associées.');
        }
        
        // Supprimer le logo
        if ($agency->logo) {
            Storage::disk('public')->delete($agency->logo);
            
            // Supprimer la miniature
            $filename = basename($agency->logo);
            $thumbnailPath = 'agencies/thumbnails/' . $filename;
            Storage::disk('public')->delete($thumbnailPath);
        }
        
        $agency->delete();
        
        return redirect()->route('agencies.index')
            ->with('success', 'Agence supprimée avec succès.');
    }

    public function agents(Agency $agency)
    {
        $this->authorize('view', $agency);
        
        $agency->load(['agents.user']);
        
        return view('agencies.agents', compact('agency'));
    }

    public function properties(Agency $agency)
    {
        $this->authorize('view', $agency);
        
        $properties = $agency->properties()->paginate(15);
        
        return view('agencies.properties', compact('agency', 'properties'));
    }

    public function addAgent(Request $request, Agency $agency)
    {
        $this->authorize('update', $agency);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'bio' => 'nullable|string',
            'specialties' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        
        // Vérifier si l'utilisateur est déjà un agent de cette agence
        if ($agency->agents()->where('user_id', $validated['user_id'])->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur est déjà un agent de cette agence.'])
                ->withInput();
        }
        
        // Ajouter l'agent
        $agency->agents()->attach($validated['user_id'], [
            'role' => $validated['role'],
            'commission_rate' => $validated['commission_rate'],
            'bio' => $validated['bio'] ?? null,
            'specialties' => json_encode($validated['specialties'] ?? []),
            'is_active' => $request->boolean('is_active'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('agencies.agents', $agency)
            ->with('success', 'Agent ajouté avec succès.');
    }

    public function updateAgent(Request $request, Agency $agency, $userId)
    {
        $this->authorize('update', $agency);
        
        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'bio' => 'nullable|string',
            'specialties' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        
        // Vérifier si l'utilisateur est un agent de cette agence
        if (!$agency->agents()->where('user_id', $userId)->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur n\'est pas un agent de cette agence.'])
                ->withInput();
        }
        
        // Mettre à jour l'agent
        $agency->agents()->updateExistingPivot($userId, [
            'role' => $validated['role'],
            'commission_rate' => $validated['commission_rate'],
            'bio' => $validated['bio'] ?? null,
            'specialties' => json_encode($validated['specialties'] ?? []),
            'is_active' => $request->boolean('is_active'),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('agencies.agents', $agency)
            ->with('success', 'Agent mis à jour avec succès.');
    }

    public function removeAgent(Agency $agency, $userId)
    {
        $this->authorize('update', $agency);
        
        // Vérifier si l'utilisateur est un agent de cette agence
        if (!$agency->agents()->where('user_id', $userId)->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur n\'est pas un agent de cette agence.'])
                ->withInput();
        }
        
        // Supprimer l'agent
        $agency->agents()->detach($userId);
        
        return redirect()->route('agencies.agents', $agency)
            ->with('success', 'Agent retiré avec succès.');
    }
}