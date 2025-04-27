<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Agent;
use App\Models\Property;
use App\Models\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Lead::query();
        
        // Filtrer par statut si spécifié
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filtrer par source si spécifié
        if ($request->has('source') && $request->source != 'all') {
            $query->where('source', $request->source);
        }
        
        // Filtrer par agent si spécifié
        if ($request->has('agent_id') && $request->agent_id != 'all') {
            $query->where('agent_id', $request->agent_id);
        }
        
        // Filtrer par recherche si spécifié
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Restreindre l'accès selon le rôle
        if ($user->role === 'agent') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
            } else {
                return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
            }
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $query->whereHas('agent', function ($q) use ($agent) {
                    $q->where('agency_id', $agent->agency_id);
                });
            } else {
                return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
            }
        }
        
        // Récupérer les leads avec pagination
        $leads = $query->with(['agent.user', 'property'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        // Récupérer les agents pour le filtre
        if ($user->role === 'admin') {
            $agents = Agent::with('user')->get();
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agents = Agent::where('agency_id', $agent->agency_id)->with('user')->get();
            } else {
                $agents = collect();
            }
        } else {
            $agents = collect();
        }
        
        // Statistiques des leads
        $totalLeads = $query->count();
        $newLeads = $query->where('status', 'new')->count();
        $convertedLeads = $query->where('status', 'converted')->count();
        
        return view('leads.index', compact(
            'leads',
            'agents',
            'totalLeads',
            'newLeads',
            'convertedLeads'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Récupérer les agents selon le rôle
        if ($user->role === 'admin') {
            $agents = Agent::with('user')->get();
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agents = Agent::where('agency_id', $agent->agency_id)->with('user')->get();
            } else {
                $agents = collect();
            }
        } else {
            $agent = Agent::where('user_id', $user->id)->first();
            $agents = $agent ? collect([$agent]) : collect();
        }
        
        // Récupérer les propriétés
        $properties = Property::where('status', 'active')->get();
        
        return view('leads.create', compact('agents', 'properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'agent_id' => 'nullable|exists:agents,id',
            'property_id' => 'nullable|exists:properties,id',
            'status' => 'required|in:new,contacted,qualified,negotiation,converted,lost',
            'source' => 'required|in:website,referral,social_media,email_campaign,phone,other',
            'notes' => 'nullable|string',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'preferred_location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
        ]);
        
        $lead = Lead::create($request->all());
        
        // Créer une activité pour le nouveau lead
        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'note',
            'description' => 'Lead créé',
        ]);
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead créé avec succès.');
    }

    public function show(Lead $lead)
    {
        $user = Auth::user();
        
        // Vérifier l'accès
        if ($user->role === 'agent') {
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent || $lead->agent_id !== $agent->id) {
                return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à ce lead.');
            }
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent || !$agent->agency_id) {
                return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à ce lead.');
            }
            
            $leadAgent = $lead->agent;
            if (!$leadAgent || $leadAgent->agency_id !== $agent->agency_id) {
                return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à ce lead.');
            }
        }
        
        $lead->load(['agent.user', 'property', 'activities.user']);
        
        // Récupérer les activités
        $activities = $lead->activities()->orderBy('created_at', 'desc')->get();
        
        // Récupérer les agents pour le transfert
        if ($user->role === 'admin') {
            $agents = Agent::with('user')->get();
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agents = Agent::where('agency_id', $agent->agency_id)->with('user')->get();
            } else {
                $agents = collect();
            }
        } else {
            $agents = collect();
        }
        
        return view('leads.show', compact('lead', 'activities', 'agents'));
    }

    public function edit(Lead $lead)
    {
        $user = Auth::user();
        
        // Vérifier l'accès
        if ($user->role === 'agent') {
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent || $lead->agent_id !== $agent->id) {
                return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à modifier ce lead.');
            }
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if (!$agent || !$agent->agency_id) {
                return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à modifier ce lead.');
            }
            
            $leadAgent = $lead->agent;
            if (!$leadAgent || $leadAgent->agency_id !== $agent->agency_id) {
                return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à modifier ce lead.');
            }
        }
        
        // Récupérer les agents selon le rôle
        if ($user->role === 'admin') {
            $agents = Agent::with('user')->get();
        } elseif ($user->role === 'agency_admin') {
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agents = Agent::where('agency_id', $agent->agency_id)->with('user')->get();
            } else {
                $agents = collect();
            }
        } else {
            $agent = Agent::where('user_id', $user->id)->first();
            $agents = $agent ? collect([$agent]) : collect();
        }
        
        // Récupérer les propriétés
        $properties = Property::where('status', 'active')->get();
        
        return view('leads.edit', compact('lead', 'agents', 'properties'));
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'agent_id' => 'nullable|exists:agents,id',
            'property_id' => 'nullable|exists:properties,id',
            'status' => 'required|in:new,contacted,qualified,negotiation,converted,lost',
            'source' => 'required|in:website,referral,social_media,email_campaign,phone,other',
            'notes' => 'nullable|string',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'preferred_location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
        ]);
        
        // Vérifier si le statut a changé
        $statusChanged = $lead->status !== $request->status;
        $oldStatus = $lead->status;
        
        // Mettre à jour les dates spéciales selon le statut
        if ($statusChanged) {
            if ($request->status === 'contacted') {
                $request->merge(['last_contacted_at' => now()]);
            } elseif ($request->status === 'converted') {
                $request->merge(['converted_at' => now()]);
            }
        }
        
        $lead->update($request->all());
        
        // Créer une activité pour le changement de statut
        if ($statusChanged) {
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'status_change',
                'description' => "Statut modifié de '{$oldStatus}' à '{$request->status}'",
            ]);
        }
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead mis à jour avec succès.');
    }

    public function destroy(Lead $lead)
    {
        $user = Auth::user();
        
        // Vérifier l'accès (seuls les admins peuvent supprimer)
        if ($user->role !== 'admin') {
            return redirect()->route('leads.index')->with('error', 'Vous n\'êtes pas autorisé à supprimer ce lead.');
        }
        
        $lead->delete();
        
        return redirect()->route('leads.index')
            ->with('success', 'Lead supprimé avec succès.');
    }

    public function addActivity(Request $request, Lead $lead)
    {
        $request->validate([
            'type' => 'required|in:note,email,call,meeting,property_visit,offer',
            'description' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);
        
        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'is_completed' => $request->has('is_completed'),
        ]);
        
        // Mettre à jour la date de dernier contact si c'est un contact
        if (in_array($request->type, ['email', 'call', 'meeting'])) {
            $lead->update(['last_contacted_at' => now()]);
        }
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Activité ajoutée avec succès.');
    }

    public function completeActivity(LeadActivity $activity)
    {
        $activity->update(['is_completed' => true]);
        
        return redirect()->route('leads.show', $activity->lead_id)
            ->with('success', 'Activité marquée comme terminée.');
    }

    public function transferLead(Request $request, Lead $lead)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'transfer_note' => 'nullable|string',
        ]);
        
        $oldAgentId = $lead->agent_id;
        $lead->update(['agent_id' => $request->agent_id]);
        
        // Créer une activité pour le transfert
        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'note',
            'description' => "Lead transféré à un nouvel agent. " . ($request->transfer_note ? "Note: {$request->transfer_note}" : ""),
        ]);
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead transféré avec succès.');
    }
}
