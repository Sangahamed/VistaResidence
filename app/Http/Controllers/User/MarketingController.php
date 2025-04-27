<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MarketingCampaign;
use App\Models\CampaignResult;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MarketingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Déterminer l'agence selon le rôle de l'utilisateur
        $agencyId = null;
        
        if ($user->role === 'admin') {
            // Admin peut voir toutes les campagnes
            $campaigns = MarketingCampaign::with('agency')->paginate(10);
        } elseif ($user->role === 'agency_admin') {
            // Agency admin voit les campagnes de son agence
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agencyId = $agent->agency_id;
                $campaigns = MarketingCampaign::where('agency_id', $agencyId)->paginate(10);
            } else {
                return redirect()->route('home')->with('error', 'Vous n\'êtes pas associé à une agence.');
            }
        } else {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }
        
        // Statistiques des campagnes
        $activeCampaigns = MarketingCampaign::where('status', 'active')
            ->when($agencyId, function ($query) use ($agencyId) {
                return $query->where('agency_id', $agencyId);
            })
            ->count();
        
        $completedCampaigns = MarketingCampaign::where('status', 'completed')
            ->when($agencyId, function ($query) use ($agencyId) {
                return $query->where('agency_id', $agencyId);
            })
            ->count();
        
        $totalBudget = MarketingCampaign::when($agencyId, function ($query) use ($agencyId) {
                return $query->where('agency_id', $agencyId);
            })
            ->sum('budget');
        
        $totalCost = MarketingCampaign::when($agencyId, function ($query) use ($agencyId) {
                return $query->where('agency_id', $agencyId);
            })
            ->sum('cost');
        
        return view('marketing.index', compact(
            'campaigns',
            'activeCampaigns',
            'completedCampaigns',
            'totalBudget',
            'totalCost'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Récupérer les agences selon le rôle
        if ($user->role === 'admin') {
            $agencies = Agency::all();
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agencies = Agency::where('id', $agent->agency_id)->get();
            } else {
                return redirect()->route('marketing.index')->with('error', 'Vous n\'êtes pas associé à une agence.');
            }
        } else {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }
        
        return view('marketing.create', compact('agencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,social_media,sms,print,web,other',
            'status' => 'required|in:draft,scheduled,active,paused,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'target_audience_size' => 'nullable|integer|min:0',
            'target_criteria' => 'nullable|string',
        ]);
        
        $campaign = MarketingCampaign::create($request->all());
        
        return redirect()->route('marketing.show', $campaign)
            ->with('success', 'Campagne marketing créée avec succès.');
    }

    public function show(MarketingCampaign $campaign)
    {
        $user = Auth::user();
        
        // Vérifier l'accès
        if ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent || $agent->agency_id !== $campaign->agency_id) {
                return redirect()->route('marketing.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette campagne.');
            }
        }
        
        $campaign->load(['agency', 'results']);
        
        // Récupérer les résultats pour les graphiques
        $results = $campaign->results()->orderBy('date')->get();
        
        // Préparer les données pour les graphiques
        $dates = $results->pluck('date')->map(function ($date) {
            return $date->format('d/m/Y');
        })->toArray();
        
        $impressions = $results->pluck('impressions')->toArray();
        $clicks = $results->pluck('clicks')->toArray();
        $leads = $results->pluck('leads_generated')->toArray();
        $conversions = $results->pluck('conversions')->toArray();
        $costs = $results->pluck('cost')->toArray();
        $revenues = $results->pluck('revenue')->toArray();
        
        // Récupérer les leads générés par cette campagne
        $campaignLeads = Lead::where('source', strtolower($campaign->type))
            ->whereHas('agent', function ($query) use ($campaign) {
                $query->where('agency_id', $campaign->agency_id);
            })
            ->whereBetween('created_at', [$campaign->start_date, $campaign->end_date ?? now()])
            ->get();
        
        return view('marketing.show', compact(
            'campaign',
            'results',
            'dates',
            'impressions',
            'clicks',
            'leads',
            'conversions',
            'costs',
            'revenues',
            'campaignLeads'
        ));
    }

    public function edit(MarketingCampaign $campaign)
    {
        $user = Auth::user();
        
        // Vérifier l'accès
        if ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent || $agent->agency_id !== $campaign->agency_id) {
                return redirect()->route('marketing.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette campagne.');
            }
        }
        
        // Récupérer les agences selon le rôle
        if ($user->role === 'admin') {
            $agencies = Agency::all();
        } else {
            $agencies = Agency::where('id', $campaign->agency_id)->get();
        }
        
        return view('marketing.edit', compact('campaign', 'agencies'));
    }

    public function update(Request $request, MarketingCampaign $campaign)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,social_media,sms,print,web,other',
            'status' => 'required|in:draft,scheduled,active,paused,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'target_audience_size' => 'nullable|integer|min:0',
            'target_criteria' => 'nullable|string',
        ]);
        
        $campaign->update($request->all());
        
        return redirect()->route('marketing.show', $campaign)
            ->with('success', 'Campagne marketing mise à jour avec succès.');
    }

    public function destroy(MarketingCampaign $campaign)
    {
        $user = Auth::user();
        
        // Vérifier l'accès (seuls les admins peuvent supprimer)
        if ($user->role !== 'admin') {
            return redirect()->route('marketing.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette campagne.');
        }
        
        $campaign->delete();
        
        return redirect()->route('marketing.index')
            ->with('success', 'Campagne marketing supprimée avec succès.');
    }

    public function addResult(Request $request, MarketingCampaign $campaign)
    {
        $request->validate([
            'date' => 'required|date',
            'impressions' => 'required|integer|min:0',
            'clicks' => 'required|integer|min:0',
            'leads_generated' => 'required|integer|min:0',
            'conversions' => 'required|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'revenue' => 'required|numeric|min:0',
        ]);
        
        // Vérifier si un résultat existe déjà pour cette date
        $existingResult = CampaignResult::where('marketing_campaign_id', $campaign->id)
            ->where('date', $request->date)
            ->first();
        
        if ($existingResult) {
            $existingResult->update($request->all());
            $message = 'Résultat de campagne mis à jour avec succès.';
        } else {
            CampaignResult::create([
                'marketing_campaign_id' => $campaign->id,
                'date' => $request->date,
                'impressions' => $request->impressions,
                'clicks' => $request->clicks,
                'leads_generated' => $request->leads_generated,
                'conversions' => $request->conversions,
                'cost' => $request->cost,
                'revenue' => $request->revenue,
            ]);
            $message = 'Résultat de campagne ajouté avec succès.';
        }
        
        // Mettre à jour le coût total de la campagne
        $totalCost = CampaignResult::where('marketing_campaign_id', $campaign->id)->sum('cost');
        $campaign->update(['cost' => $totalCost]);
        
        return redirect()->route('marketing.show', $campaign)
            ->with('success', $message);
    }

    public function deleteResult(CampaignResult $result)
    {
        $campaign = $result->campaign;
        $result->delete();
        
        // Mettre à jour le coût total de la campagne
        $totalCost = CampaignResult::where('marketing_campaign_id', $campaign->id)->sum('cost');
        $campaign->update(['cost' => $totalCost]);
        
        return redirect()->route('marketing.show', $campaign)
            ->with('success', 'Résultat de campagne supprimé avec succès.');
    }
}
