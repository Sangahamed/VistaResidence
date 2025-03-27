<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountTypeController extends Controller
{
    public function becomeProprietaire()
{
    $user = Auth::user();
    
    if (!$user->hasRole('particulier')) {
        $user->syncRoles(['particulier']); // Remplace tous les rôles
        return redirect()->route('proprietaire.dashboard')
               ->with('success', 'Vous êtes maintenant propriétaire!');
    }

    return back()->with('info', 'Vous êtes déjà propriétaire.');
}

public function createEnterprise(Request $request)
{
    $request->validate(['entreprise_name' => 'required|string|max:255']);

    $user = Auth::user();
    $entreprise = $user->enterprises()->create(['name' => $request->entreprise_name]);

    $user->syncRoles(['admin_entreprise']);
    return redirect()->route('entreprise.dashboard')
           ->with('success', 'Entreprise créée avec succès!');
}
}
