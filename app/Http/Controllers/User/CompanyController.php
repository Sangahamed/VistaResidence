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

    public function pending()
    {
        $user = auth()->user();
        $company = $user->company;

        return view('companies.pending', compact('company'));
    }

    public function index()
    {
        $query = Company::withCount(['projects', 'teams', 'users']);
    
        if (!auth()->user()->hasPermission('manage-all-companies')) {
            $query->whereHas('users', function($q) {
                $q->where('users.id', auth()->id());
            });
        }
    
        $companies = $query->latest()->paginate(10);
    
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $user = auth()->user();

        // Si déjà une entreprise (active ou en attente)
        if ($user->hasCompany()) {
            if ($user->hasPendingCompany()) {
                return redirect()->route('companies.pending');
            }
            return redirect()->route('companies.index');
        }

        $modules = Module::all();
        // Si pas une entreprise (mais account_type = company)
        if ($user->isCompany() && !$user->hasCompany() || $user->isIndividual()) {
                return view('companies.create', compact('modules'));
            }

        // Si pas autorisé à créer
        abort(403, 'Vous ne pouvez pas créer d\'entreprise');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->hasCompany()) {
            return back()->with('error', 'Vous avez déjà une entreprise');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        // Set the current user as owner
        $validated['owner_id'] = auth()->id();

        // Create company
        $company = Company::create($validated);

        // Attach the current user to the company as admin
        $company->users()->attach(auth()->id(), [
            'job_title' => 'Administrateur',
            'is_admin' => true,
        ]);

        // Attach selected modules
        if ($request->has('modules')) {
            $moduleData = [];
            foreach ($request->modules as $moduleId) {
                $moduleData[$moduleId] = [
                    'is_enabled' => true,
                    'settings' => json_encode([]),
                    'expires_at' => now()->addYear(),
                ];
            }
            $company->modules()->attach($moduleData);
        }

        return redirect()->route('companies.show', $company)
            ->with('success', 'Entreprise créée avec succès.');
    }

    public function show(Company $company)
    {
        // $this->authorize('view', $company);

        $recentProjects = $company->projects()->latest()->take(5)->get();
        $tasksCount = $company->projects->sum(function ($project) {
            return $project->tasks->count();
        });

        return view('companies.show', compact('company', 'recentProjects', 'tasksCount'));
    }

    public function edit(Company $company)
    {
        // $this->authorize('update', $company);

        $modules = Module::all();
        $companyModules = $company->modules->pluck('id')->toArray();

        return view('companies.edit', compact('company', 'modules', 'companyModules'));
    }

    public function update(Request $request, Company $company)
    {
        // $this->authorize('update', $company);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        // Update company
        $company->update($validated);

        // Update modules
        if ($request->has('modules')) {
            $moduleData = [];
            foreach ($request->modules as $moduleId) {
                // Check if module was already attached
                if ($company->modules->contains($moduleId)) {
                    // Keep existing settings
                    $existingPivot = $company->modules->find($moduleId)->pivot;
                    $moduleData[$moduleId] = [
                        'is_enabled' => $existingPivot->is_enabled,
                        'settings' => $existingPivot->settings,
                        'expires_at' => $existingPivot->expires_at,
                    ];
                } else {
                    // New module
                    $moduleData[$moduleId] = [
                        'is_enabled' => true,
                        'settings' => json_encode([]),
                        'expires_at' => now()->addYear(),
                    ];
                }
            }
            $company->modules()->sync($moduleData);
        } else {
            $company->modules()->detach();
        }

        return redirect()->route('companies.show', $company)
            ->with('success', 'Entreprise mise à jour avec succès.');
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        // Check if user is the owner
        if ($company->owner_id !== auth()->id() && !auth()->user()->hasPermission('manage-all-companies')) {
            return redirect()->route('companies.index')
                ->with('error', 'Vous n\'avez pas les droits pour supprimer cette entreprise.');
        }

        // Delete logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        // Delete company
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Entreprise supprimée avec succès.');
    }
}
