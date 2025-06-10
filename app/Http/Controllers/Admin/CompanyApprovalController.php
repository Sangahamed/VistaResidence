<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        // Récupération des paramètres de filtrage
        $status = $request->input('status', 'all');
        $date = $request->input('date', 'all');
        $sort = $request->input('sort', 'newest');

        // Construction de la requête
        $query = Company::with(['owner', 'processedBy'])
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($date !== 'all', function ($q) use ($date) {
                $dateRange = $this->getDateRange($date);
                return $q->whereBetween('created_at', $dateRange);
            });

        // Tri des résultats
        $query->orderBy('created_at', $sort === 'newest' ? 'desc' : 'asc');

        $companies = $query->paginate(10);
        $admins = Admin::all();

        return view('back.admin.companies.index', compact('companies', 'status', 'date', 'sort', 'admins'));
    }

    public function approve(Company $company)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return back()->with('error', "Vous devez être connecté en tant qu'administrateur.");
        }

        DB::beginTransaction();
        try {
            // Mise à jour du statut de l'entreprise
            $company->update([
                'status' => Company::STATUS_APPROVED,
                'approved_at' => now(),
                'processed_by' => $admin->id, // ID de l'admin qui approuve
                'rejection_reason' => null,
            ]);

            // Mise à jour du propriétaire de l'entreprise
            $owner = $company->owner;
            if ($owner) {
                $owner->update([
                    'account_type' => 'company',
                    'role' => 'admin'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.companies.index')
                ->with('success', "L'entreprise {$company->name} a été approuvée avec succès.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erreur lors de l'approbation de l'entreprise: " . $e->getMessage());
            return back()->with('error', "Une erreur est survenue lors de l'approbation: {$e->getMessage()}");
        }
    }

    public function reject(Request $request, Company $company)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return back()->with('error', "Vous devez être connecté en tant qu'administrateur.");
        }

        DB::beginTransaction();
        try {
            // Mise à jour de la société pour le rejet
            $company->update([
                'status' => Company::STATUS_REJECTED,
                'rejection_reason' => $request->rejection_reason,
                'processed_by' => $admin->id, // ID de l'admin qui rejette
                'rejected_at' => now()
            ]);

            DB::commit();
            return redirect()->route('admin.companies.index')
                ->with('success', "L'entreprise {$company->name} a été rejetée.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erreur lors du rejet de l'entreprise: " . $e->getMessage());
            return back()->with('error', "Une erreur est survenue lors du rejet: {$e->getMessage()}");
        }
    }

    public function updateStatus(Request $request, Company $company)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected|string|max:500'
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return back()->with('error', "Vous devez être connecté en tant qu'administrateur.");
        }

        DB::beginTransaction();
        try {
            $updateData = [
                'status' => $request->status,
                'processed_by' => $admin->id, // ID de l'admin qui met à jour
            ];

            if ($request->status === Company::STATUS_APPROVED) {
                $updateData['approved_at'] = now();
                $updateData['rejection_reason'] = null;
                
                // Mise à jour du propriétaire
                $owner = $company->owner;
                if ($owner) {
                    $owner->update([
                        'account_type' => 'company',
                        'role' => 'admin'
                    ]);
                }
            } elseif ($request->status === Company::STATUS_REJECTED) {
                $updateData['rejection_reason'] = $request->rejection_reason;
                $updateData['rejected_at'] = now();
            }

            $company->update($updateData);
            
            DB::commit();
            return back()->with('success', 'Statut mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erreur lors de la mise à jour du statut: " . $e->getMessage());
            return back()->with('error', "Une erreur est survenue lors de la mise à jour: {$e->getMessage()}");
        }
    }

    private function getDateRange($date)
    {
        switch ($date) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'week':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'year':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return [now()->subYears(10), now()];
        }
    }
}
