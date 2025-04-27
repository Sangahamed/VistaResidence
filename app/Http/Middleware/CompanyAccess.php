<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Company;

class CompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $companyId = $request->route('company');
        
        if (!$companyId) {
            return redirect()->route('companies.index')->with('error', 'Entreprise non spécifiée.');
        }
        
        $company = Company::find($companyId);
        
        if (!$company) {
            return redirect()->route('companies.index')->with('error', 'Entreprise introuvable.');
        }
        
        // Vérifier si l'utilisateur a accès à cette entreprise
        $user = $request->user();
        
        if (!$user->companies->contains($company->id) && !$user->hasPermission('manage-all-companies')) {
            return redirect()->route('companies.index')->with('error', 'Vous n\'avez pas accès à cette entreprise.');
        }
        
        // Ajouter l'entreprise à la requête pour y accéder facilement dans les contrôleurs
        $request->company = $company;
        
        return $next($request);
    }
}
