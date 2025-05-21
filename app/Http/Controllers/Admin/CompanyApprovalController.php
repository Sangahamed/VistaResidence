<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Role; // Ajoutez cette ligne pour importer le modèle Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Ajoutez cette ligne pour importer la façade DB

class CompanyApprovalController extends Controller
{
    public function pending()
    {
        $pendingCompanies = Company::with(['owner'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('back.admin.companies.pending', compact('pendingCompanies'));
    }

    public function approve(Company $company)
    {
        DB::transaction(function () use ($company) {
            // Mettre à jour le statut de l'entreprise
            $company->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);
            
            // Vérifier si le propriétaire existe
            $owner = $company->owner;
            if ($owner) {
                // Attribuer le rôle admin à l'owner
                $adminRole = Role::where('name', 'admin')->first();
                if ($adminRole) {
                    if (!$company->owner) {
                        \Log::error("Propriétaire non trouvé pour l'entreprise: " . $company->id);
                    } else {
                        \Log::info("Propriétaire trouvé: " . $company->owner->name);
                    }
                    
                } else {
                    // Si le rôle 'admin' n'existe pas, vous pouvez gérer cette situation
                    \Log::error("Le rôle 'admin' n'existe pas.");
                    return redirect()->route('admin.companies.pending')
                        ->with('error', "Le rôle 'admin' est introuvable.");
                }
            } else {
                // Si le propriétaire n'existe pas, vous pouvez gérer cette situation
                \Log::error("Propriétaire non trouvé pour l'entreprise {$company->name}");
                return redirect()->route('admin.companies.pending')
                    ->with('error', "Le propriétaire de l'entreprise {$company->name} est introuvable.");
            }
        });
    
        return redirect()->route('admin.companies.pending')
            ->with('success', "L'entreprise {$company->name} a été approuvée. Le créateur est maintenant admin.");
    }
    


    public function reject(Request $request, Company $company)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $company->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('admin.companies.pending')
            ->with('success', "L'entreprise {$company->name} a été rejetée.");
    }
}
