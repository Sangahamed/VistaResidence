<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Agent::class);
        
        $query = Agent::query();
        
        // Filtres
        if ($request->has('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }
        
        if ($request->has('specialization')) {
            $query->where('specialization', $request->specialization);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhere('license_number', 'like', "%{$search}%")
            ->orWhere('phone_number', 'like', "%{$search}%");
        }
        
        // Filtrer selon le rôle de l'utilisateur
        $user = Auth::user();
        
        if ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $query->whereHas('agency', function($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            });
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $query->where('agency_id', $agencyId);
        }
        
        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        $agents = $query->with(['user', 'agency'])->paginate(15);
        
        // Données pour les filtres
        $agencies = [];
        $specializations = Agent::distinct('specialization')->pluck('specialization')->filter();
        
        if ($user->isSuperAdmin()) {
            $agencies = Agency::all();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $agencies = Agency::whereIn('company_id', $companyIds)->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $agencies = Agency::where('id', $agencyId)->get();
        }
        
        return view('agents.index', compact('agents', 'agencies', 'specializations'));
    }

    public function create()
    {
        $this->authorize('create', Agent::class);
        
        $agencies = [];
        $users = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $agencies = Agency::all();
            $users = User::whereDoesntHave('agent')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $agencies = Agency::whereIn('company_id', $companyIds)->get();
            $users = User::whereHas('companies', function($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            })->whereDoesntHave('agent')->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $agencies = Agency::where('id', $agencyId)->get();
            $users = User::whereHas('companies', function($q) use ($agencyId) {
                $q->whereHas('agencies', function($q) use ($agencyId) {
                    $q->where('id', $agencyId);
                });
            })->whereDoesntHave('agent')->get();
        }
        
        return view('agents.create', compact('agencies', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Agent::class);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'agency_id' => 'required|exists:agencies,id',
            'license_number' => 'required|string|max:255|unique:agents',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'years_of_experience' => 'nullable|integer|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit de créer un agent pour cette agence
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            if ($user->isCompanyAdmin()) {
                $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
                $agency = Agency::find($validated['agency_id']);
                if (!$companyIds->contains($agency->company_id)) {
                    return redirect()->back()
                        ->withErrors(['agency_id' => 'Vous n\'avez pas le droit de créer un agent pour cette agence.'])
                        ->withInput();
                }
            } elseif ($user->isAgencyAdmin()) {
                $agencyId = $user->agent->agency_id;
                if ($agencyId != $validated['agency_id']) {
                    return redirect()->back()
                        ->withErrors(['agency_id' => 'Vous n\'avez pas le droit de créer un agent pour cette agence.'])
                        ->withInput();
                }
            }
        }
        
        // Vérifier que l'utilisateur n'est pas déjà un agent
        if (Agent::where('user_id', $validated['user_id'])->exists()) {
            return redirect()->back()
                ->withErrors(['user_id' => 'Cet utilisateur est déjà un agent.'])
                ->withInput();
        }
        
        $agent = new Agent();
        $agent->fill($validated);
        $agent->is_active = $request->boolean('is_active');
        
        // Traiter l'image de profil
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('agents/profiles', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($image)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'agents/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $agent->profile_image = $path;
        }
        
        $agent->save();
        
        return redirect()->route('agents.show', $agent)
            ->with('success', 'Agent créé avec succès.');
    }

    public function show(Agent $agent)
    {
        $this->authorize('view', $agent);
        
        $agent->load(['user', 'agency', 'properties', 'leads']);
        
        return view('agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        $this->authorize('update', $agent);
        
        $agencies = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $agencies = Agency::all();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $agencies = Agency::whereIn('company_id', $companyIds)->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $agencies = Agency::where('id', $agencyId)->get();
        }
        
        return view('agents.edit', compact('agent', 'agencies'));
    }

    public function update(Request $request, Agent $agent)
    {
        $this->authorize('update', $agent);
        
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'license_number' => 'required|string|max:255|unique:agents,license_number,' . $agent->id,
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'years_of_experience' => 'nullable|integer|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Vérifier que l'utilisateur a le droit de modifier cet agent
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $agent->agency_id !== $validated['agency_id']) {
            if ($user->isCompanyAdmin()) {
                $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
                $agency = Agency::find($validated['agency_id']);
                if (!$companyIds->contains($agency->company_id)) {
                    return redirect()->back()
                        ->withErrors(['agency_id' => 'Vous n\'avez pas le droit de modifier l\'agence de cet agent.'])
                        ->withInput();
                }
            } elseif ($user->isAgencyAdmin()) {
                $agencyId = $user->agent->agency_id;
                if ($agencyId != $validated['agency_id']) {
                    return redirect()->back()
                        ->withErrors(['agency_id' => 'Vous n\'avez pas le droit de modifier l\'agence de cet agent.'])
                        ->withInput();
                }
            }
        }
        
        $agent->fill($validated);
        $agent->is_active = $request->boolean('is_active');
        
        // Traiter l'image de profil
        if ($request->hasFile('profile_image')) {
            // Supprimer l'ancienne image
            if ($agent->profile_image) {
                Storage::disk('public')->delete($agent->profile_image);
                
                // Supprimer la miniature
                $oldFilename = basename($agent->profile_image);
                $oldThumbnailPath = 'agents/thumbnails/' . $oldFilename;
                Storage::disk('public')->delete($oldThumbnailPath);
            }
            
            $image = $request->file('profile_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('agents/profiles', $filename, 'public');
            
            // Créer une miniature
            $thumbnail = Image::make($image)
                ->fit(200, 200)
                ->encode();
                
            $thumbnailPath = 'agents/thumbnails/' . $filename;
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            $agent->profile_image = $path;
        }
        
        $agent->save();
        
        return redirect()->route('agents.show', $agent)
            ->with('success', 'Agent mis à jour avec succès.');
    }

    public function destroy(Agent $agent)
    {
        $this->authorize('delete', $agent);
        
        // Vérifier si l'agent a des propriétés
        if ($agent->properties()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet agent car il a des propriétés associées.');
        }
        
        // Vérifier si l'agent a des leads
        if ($agent->leads()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet agent car il a des leads associés.');
        }
        
        // Supprimer l'image de profil
        if ($agent->profile_image) {
            Storage::disk('public')->delete($agent->profile_image);
            
            // Supprimer la miniature
            $filename = basename($agent->profile_image);
            $thumbnailPath = 'agents/thumbnails/' . $filename;
            Storage::disk('public')->delete($thumbnailPath);
        }
        
        $agent->delete();
        
        return redirect()->route('agents.index')
            ->with('success', 'Agent supprimé avec succès.');
    }

    public function properties(Agent $agent)
    {
        $this->authorize('view', $agent);
        
        $properties = $agent->properties()->paginate(15);
        
        return view('agents.properties', compact('agent', 'properties'));
    }

    public function leads(Agent $agent)
    {
        $this->authorize('view', $agent);
        
        $leads = $agent->leads()->paginate(15);
        
        return view('agents.leads', compact('agent', 'leads'));
    }
}
