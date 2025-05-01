<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use App\Models\Team;
use App\Models\Message;
use App\Models\Favorite;
use App\Models\Visit;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ChMessage;



class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function messages()
    {
        $userId = Auth::id();

        // Récupérer les derniers messages groupés par utilisateur correspondant à l'utilisateur connecté
        $conversations = ChMessage::select(DB::raw('IF(from_id = '.$userId.', to_id, from_id) as user_id'))
            ->where(function ($query) use ($userId) {
                $query->where('from_id', $userId)
                    ->orWhere('to_id', $userId);
            })
            ->groupBy('user_id')
            ->get();

        return view('dashboard.messages', compact('conversations'));
    }

    public function index()
    {
        // Rediriger vers le tableau de bord approprié en fonction du type de compte
        $accountType = auth()->user()->account_type;
        
        if ($accountType === 'client') {
            return $this->clientDashboard();
        } elseif ($accountType === 'individual') {
            return $this->individualDashboard();
        } elseif ($accountType === 'company') {
            return $this->companyDashboard();
        }
        
        // Fallback au tableau de bord client par défaut
        return $this->clientDashboard();
    }

    /**
     * Tableau de bord pour les clients (acheteurs/locataires)
     */
    public function clientDashboard()
    {
        $user = auth()->user();
        
        // Statistiques de base
        $stats = [
            'properties_viewed' => Visit::where('user_id', $user->id)->count(),
            'favorites_count' => Favorite::where('user_id', $user->id)->count(),
            'messages_count' => Message::where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->count(),
            'searches_count' => 3, // À remplacer par le nombre réel de recherches sauvegardées
        ];
        
        // Propriétés récemment visitées
        $recentVisits = Visit::with('property')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Propriétés favorites
        $favorites = Favorite::with('property')
            ->where('user_id', $user->id)
            ->latest()
            ->take(6)
            ->get();
        
        // Messages récents
        $messages = Message::with(['sender', 'recipient'])
            ->where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Recherches sauvegardées (à implémenter)
        $savedSearches = [];
        
        return view('dashboard.client', compact(
            'stats', 
            'recentVisits', 
            'favorites', 
            'messages', 
            'savedSearches'
        ));
    }

    /**
     * Tableau de bord pour les particuliers (propriétaires)
     */
    public function individualDashboard()
    {
        $user = auth()->user();
        
        // Statistiques de base
        $stats = [
            'properties_count' => Property::where('owner_id', $user->id)->count(),
            'active_listings' => Property::where('owner_id', $user->id)
                ->where('status', 'active')
                ->count(),
            'rental_income' => Contract::where('owner_id', $user->id)
                ->where('type', 'rental')
                ->where('status', 'active')
                ->sum('amount'),
            'pending_visits' => Visit::whereHas('property', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->where('status', 'pending')
            ->count(),
        ];
        
        // Propriétés de l'utilisateur
        $properties = Property::where('owner_id', $user->id)
            ->latest()
            ->take(6)
            ->get();
        
        // Visites à venir
        $upcomingVisits = Visit::with(['property', 'user'])
            ->whereHas('property', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->where('status', 'confirmed')
            ->where('visit_date', '>=', now())
            ->orderBy('visit_date')
            ->take(5)
            ->get();
        
        // Contrats actifs
        $activeContracts = Contract::with('property')
            ->where('owner_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();
        
        // Données pour le graphique des revenus locatifs (6 derniers mois)
        $rentalData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $amount = Contract::where('owner_id', $user->id)
                ->where('type', 'rental')
                ->where('status', 'active')
                ->whereMonth('start_date', '<=', $month)
                ->whereMonth('end_date', '>=', $month)
                ->sum('amount');
            
            $rentalData[] = [
                'month' => $month->format('M'),
                'amount' => $amount
            ];
        }
        
        return view('dashboard.individual', compact(
            'stats', 
            'properties', 
            'upcomingVisits', 
            'activeContracts', 
            'rentalData'
        ));
    }

    /**
     * Tableau de bord pour les entreprises (agences immobilières)
     */
    public function companyDashboard()
    {
        $user = auth()->user();
        $company = $user->company;
    
        if (!$company) {
            return redirect()->route('company.create')->with('info', 'Vous devez d\'abord créer votre entreprise.');
        }
    
        if ($company->isPending()) {
            return view('dashboard.company.pending');
        }
    
        if ($company->isRejected()) {
            return view('dashboard.company.rejected');
        }
    
        $companyIds = $user->hasRole('super-admin') 
            ? Company::pluck('id')->toArray() 
            : $user->companies->pluck('id')->toArray();
    
        $agencyIds = \App\Models\Agency::whereIn('company_id', $companyIds)->pluck('id')->toArray();
    
        $stats = [
            'properties_count' => Property::whereIn('company_id', $companyIds)->count(),
            'team_members' => \DB::table('company_user')->whereIn('company_id', $companyIds)->distinct('user_id')->count('user_id'),
            'active_projects' => Project::whereIn('company_id', $companyIds)->where('status', 'in_progress')->count(),
            'completed_tasks' => Task::whereHas('project', fn($q) => $q->whereIn('company_id', $companyIds))->where('status', 'completed')->count(),
            'agencies' => count($agencyIds),
            'companies' => count($companyIds),
            'users' => \DB::table('company_user')->whereIn('company_id', $companyIds)->distinct('user_id')->count('user_id') + 
                       \DB::table('agency_agent')->whereIn('agency_id', $agencyIds)->distinct('user_id')->count('user_id'),
            'projects' => Project::whereIn('company_id', $companyIds)->count(),
            'tasks' => Task::whereHas('project', fn($q) => $q->whereIn('company_id', $companyIds))->count(),
        ];
    
        $recentProjects = Project::whereIn('company_id', $companyIds)->latest()->take(5)->get();
        $recentTasks = Task::whereHas('project', fn($q) => $q->whereIn('company_id', $companyIds))->latest()->take(5)->get();
        $properties = Property::whereIn('company_id', $companyIds)->latest()->take(6)->get();
    
        return view('dashboard.company', compact('stats', 'recentProjects', 'recentTasks', 'properties'));
    }

    public function switchToIndividual()
    {
        $user = Auth::user();

        if ($user->isClient()) {
            $user->account_type = 'individual';
            $user->role = 'agent';
            $user->save();

            return redirect()->route('dashboard.individual')->with('success', 'Vous êtes maintenant un particulier.');
        }

        return back()->with('error', 'Vous ne pouvez pas effectuer cette action.');
    }    

}
