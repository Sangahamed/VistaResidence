<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Individual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:255|unique:users,phone,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updateAccountType(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:client,individual,company',
        ]);

        $user = Auth::user();
        $oldType = $user->account_type;
        $newType = $request->account_type;

        // Si le type ne change pas, ne rien faire
        if ($oldType === $newType) {
            return back()->with('info', 'Aucun changement de type de compte.');
        }

        // Changement vers "individual"
        if ($newType === 'individual') {
            $user->account_type = 'individual';
            $user->save();

            // Créer l'entrée Individual si elle n'existe pas
            if (!$user->individual) {
                Individual::create(['user_id' => $user->id]);
            }

            return redirect()->route('dashboard.individual')
                ->with('success', 'Votre compte a été mis à jour en tant que Particulier.');
        }

        // Changement vers "company"
        if ($newType === 'company') {
            // Vérifier si une demande existe déjà
            $existingCompany = Company::where('user_id', $user->id)->first();

            if ($existingCompany) {
                if ($existingCompany->status === 'approved') {
                    $user->account_type = 'company';
                    $user->save();

                    return redirect()->route('dashboard.company')
                        ->with('success', 'Votre compte a été mis à jour en tant qu\'Entreprise.');
                } else {
                    return back()->with('info', 'Votre demande d\'entreprise est en attente d\'approbation.');
                }
            }

            // Rediriger vers le formulaire de création d'entreprise
            return redirect()->route('company.create');
        }

        // Changement vers "client"
        if ($newType === 'client') {
            $user->account_type = 'client';
            $user->save();

            return redirect()->route('dashboard.client')
                ->with('success', 'Votre compte a été mis à jour en tant que Client.');
        }
    }
}
