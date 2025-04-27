<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-modules')->except(['index', 'show']);
    }

    public function index()
    {
        $modules = Module::all();
        return view('modules.index', compact('modules'));
    }

    public function create()
    {
        return view('modules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'required|string|max:50',
            'is_enabled' => 'boolean',
            'is_core' => 'boolean',
            'settings' => 'nullable|json',
        ]);

        Module::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'version' => $request->version,
            'is_enabled' => $request->is_enabled ?? true,
            'is_core' => $request->is_core ?? false,
            'settings' => $request->settings,
        ]);

        return redirect()->route('modules.index')
            ->with('success', 'Module créé avec succès.');
    }

    public function show(Module $module)
    {
        $companies = $module->companies;
        return view('modules.show', compact('module', 'companies'));
    }

    public function edit(Module $module)
    {
        return view('modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'required|string|max:50',
            'is_enabled' => 'boolean',
            'is_core' => 'boolean',
            'settings' => 'nullable|json',
        ]);

        $module->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'version' => $request->version,
            'is_enabled' => $request->is_enabled ?? true,
            'is_core' => $request->is_core ?? false,
            'settings' => $request->settings,
        ]);

        return redirect()->route('modules.index')
            ->with('success', 'Module mis à jour avec succès.');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route('modules.index')
            ->with('success', 'Module supprimé avec succès.');
    }

    public function assignToCompany(Request $request, Module $module)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'is_enabled' => 'boolean',
            'settings' => 'nullable|json',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $company = Company::findOrFail($request->company_id);

        $company->modules()->syncWithoutDetaching([
            $module->id => [
                'is_enabled' => $request->is_enabled ?? true,
                'settings' => $request->settings,
                'expires_at' => $request->expires_at,
            ]
        ]);

        return back()->with('success', 'Module assigné à l\'entreprise avec succès.');
    }

    public function removeFromCompany(Module $module, Company $company)
    {
        $company->modules()->detach($module->id);
        return back()->with('success', 'Module retiré de l\'entreprise avec succès.');
    }
}
