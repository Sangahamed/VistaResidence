<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-roles')->only(['index', 'show']);
        $this->middleware('permission:create-roles')->only(['create', 'store']);
        $this->middleware('permission:edit-roles')->only(['edit', 'update']);
        $this->middleware('permission:delete-roles')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'is_default' => 'boolean',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_default' => $request->is_default ?? false,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        // Si ce rôle est défini comme par défaut, désactiver les autres rôles par défaut
        if ($request->is_default) {
            Role::where('id', '!=', $role->id)->update(['is_default' => false]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'is_default' => 'boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_default' => $request->is_default ?? false,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        // Si ce rôle est défini comme par défaut, désactiver les autres rôles par défaut
        if ($request->is_default) {
            Role::where('id', '!=', $role->id)->update(['is_default' => false]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès.');
    }
}
