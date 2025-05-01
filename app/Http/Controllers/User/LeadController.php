<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Agent;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewLeadNotification;
use App\Notifications\LeadAssignedNotification;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Lead::class);
        
        $query = Lead::query();
        
        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('source')) {
            $query->where('source', $request->source);
        }
        
        if ($request->has('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }
        
        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Filtrer selon le rôle de l'utilisateur
        $user = Auth::user();
        
        if ($user->isAgent()) {
            $query->where('agent_id', $user->agent->id);
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $query->whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            });
        } elseif ($user->isCompanyAdmin()) {
            $companyId = $user->companies()->wherePivot('is_admin', true)->first()->id;
            $query->whereHas('agent', function($q) use ($companyId) {
                $q->whereHas('agency', function($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            });
        }
        
        $leads = $query->with(['agent', 'property'])->paginate(15);
        
        // Données pour les filtres
        $statuses = [
            'new' => 'Nouveau',
            'contacted' => 'Contacté',
            'qualified' => 'Qualifié',
            'negotiation' => 'En négociation',
            'converted' => 'Converti',
            'lost' => 'Perdu'
        ];
        
        $sources = [
            'website' => 'Site web',
            'referral' => 'Recommandation',
            'social_media' => 'Réseaux sociaux',
            'email_campaign' => 'Campagne email',
            'phone' => 'Téléphone',
            'other' => 'Autre'
        ];
        
        $agents = [];
        
        if ($user->isSuperAdmin()) {
            $agents = Agent::with('user')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyId = $user->companies()->wherePivot('is_admin', true)->first()->id;
            $agents = Agent::whereHas('agency', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->with('user')->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $agents = Agent::where('agency_id', $agencyId)->with('user')->get();
        }
        
        return view('leads.index', compact('leads', 'statuses', 'sources', 'agents'));
    }

    public function create()
    {
        $this->authorize('create', Lead::class);
        
        $properties = [];
        $agents = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 'active')->get();
            $agents = Agent::with('user')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyId = $user->companies()->wherePivot('is_admin', true)->first()->id;
            $properties = Property::where('company_id', $companyId)->where('status', 'active')->get();
            $agents = Agent::whereHas('agency', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->with('user')->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $properties = Property::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->where('status', 'active')->get();
            $agents = Agent::where('agency_id', $agencyId)->with('user')->get();
        } elseif ($user->isAgent()) {
            $properties = Property::where('agent_id', $user->agent->id)->where('status', 'active')->get();
            $agents = [$user->agent];
        }
        
        $statuses = [
            'new' => 'Nouveau',
            'contacted' => 'Contacté',
            'qualified' => 'Qualifié',
            'negotiation' => 'En négociation',
            'converted' => 'Converti',
            'lost' => 'Perdu'
        ];
        
        $sources = [
            'website' => 'Site web',
            'referral' => 'Recommandation',
            'social_media' => 'Réseaux sociaux',
            'email_campaign' => 'Campagne email',
            'phone' => 'Téléphone',
            'other' => 'Autre'
        ];
        
        return view('leads.create', compact('properties', 'agents', 'statuses', 'sources'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lead::class);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,negotiation,converted,lost',
            'source' => 'required|in:website,referral,social_media,email_campaign,phone,other',
            'notes' => 'nullable|string',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0|gte:budget_min',
            'preferred_location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'property_id' => 'nullable|exists:properties,id',
            'agent_id' => 'nullable|exists:agents,id',
        ]);
        
        $lead = new Lead();
        $lead->fill($validated);
        
        // Si aucun agent n'est spécifié et que l'utilisateur est un agent, assigner automatiquement
        if (!$request->has('agent_id') && Auth::user()->isAgent()) {
            $lead->agent_id = Auth::user()->agent->id;
        }
        
        $lead->save();
        
        // Créer une activité pour la création du lead
        $activity = new LeadActivity();
        $activity->lead_id = $lead->id;
        $activity->user_id = Auth::id();
        $activity->type = 'note';
        $activity->description = 'Lead créé';
        $activity->save();
        
        // Notifier l'agent assigné
        if ($lead->agent_id) {
            $agent = Agent::find($lead->agent_id);
            if ($agent && $agent->user) {
                $agent->user->notify(new LeadAssignedNotification($lead));
            }
        }
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead créé avec succès.');
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);
        
        $lead->load(['agent', 'property', 'activities.user']);
        
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);
        
        $properties = [];
        $agents = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 'active')->get();
            $agents = Agent::with('user')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyId = $user->companies()->wherePivot('is_admin', true)->first()->id;
            $properties = Property::where('company_id', $companyId)->where('status', 'active')->get();
            $agents = Agent::whereHas('agency', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->with('user')->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $properties = Property::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->where('status', 'active')->get();
            $agents = Agent::where('agency_id', $agencyId)->with('user')->get();
        } elseif ($user->isAgent()) {
            $properties = Property::where('agent_id', $user->agent->id)->where('status', 'active')->get();
            $agents = [$user->agent];
        }
        
        $statuses = [
            'new' => 'Nouveau',
            'contacted' => 'Contacté',
            'qualified' => 'Qualifié',
            'negotiation' => 'En négociation',
            'converted' => 'Converti',
            'lost' => 'Perdu'
        ];
        
        $sources = [
            'website' => 'Site web',
            'referral' => 'Recommandation',
            'social_media' => 'Réseaux sociaux',
            'email_campaign' => 'Campagne email',
            'phone' => 'Téléphone',
            'other' => 'Autre'
        ];
        
        return view('leads.edit', compact('lead', 'properties', 'agents', 'statuses', 'sources'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,negotiation,converted,lost',
            'source' => 'required|in:website,referral,social_media,email_campaign,phone,other',
            'notes' => 'nullable|string',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0|gte:budget_min',
            'preferred_location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'property_id' => 'nullable|exists:properties,id',
            'agent_id' => 'nullable|exists:agents,id',
        ]);
        
        // Vérifier si le statut a changé
        $statusChanged = $lead->status !== $validated['status'];
        $oldStatus = $lead->status;
        
        // Vérifier si l'agent a changé
        $agentChanged = $lead->agent_id !== $validated['agent_id'];
        $oldAgentId = $lead->agent_id;
        
        $lead->fill($validated);
        
        // Si le statut est passé à "converted", enregistrer la date de conversion
        if ($statusChanged && $validated['status'] === 'converted') {
            $lead->converted_at = now();
        }
        
        // Si le lead est contacté, mettre à jour la date de dernier contact
        if ($statusChanged && $validated['status'] === 'contacted') {
            $lead->last_contacted_at = now();
        }
        
        $lead->save();
        
        // Créer une activité pour le changement de statut
        if ($statusChanged) {
            $activity = new LeadActivity();
            $activity->lead_id = $lead->id;
            $activity->user_id = Auth::id();
            $activity->type = 'status_change';
            $activity->description = "Statut modifié de '{$oldStatus}' à '{$validated['status']}'";
            $activity->save();
        }
        
        // Créer une activité pour le changement d'agent
        if ($agentChanged) {
            $activity = new LeadActivity();
            $activity->lead_id = $lead->id;
            $activity->user_id = Auth::id();
            $activity->type = 'note';
            
            if ($oldAgentId) {
                $oldAgent = Agent::find($oldAgentId);
                $oldAgentName = $oldAgent ? $oldAgent->user->name : 'Inconnu';
                
                if ($validated['agent_id']) {
                    $newAgent = Agent::find($validated['agent_id']);
                    $newAgentName = $newAgent ? $newAgent->user->name : 'Inconnu';
                    $activity->description = "Agent modifié de '{$oldAgentName}' à '{$newAgentName}'";
                } else {
                    $activity->description = "Agent '{$oldAgentName}' retiré";
                }
            } else {
                if ($validated['agent_id']) {
                    $newAgent = Agent::find($validated['agent_id']);
                    $newAgentName = $newAgent ? $newAgent->user->name : 'Inconnu';
                    $activity->description = "Agent '{$newAgentName}' assigné";
                    
                    // Notifier le nouvel agent
                    if ($newAgent && $newAgent->user) {
                        $newAgent->user->notify(new LeadAssignedNotification($lead));
                    }
                }
            }
            
            $activity->save();
        }
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead mis à jour avec succès.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        
        // Supprimer les activités associées
        $lead->activities()->delete();
        
        $lead->delete();
        
        return redirect()->route('leads.index')
            ->with('success', 'Lead supprimé avec succès.');
    }

    public function addActivity(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        
        $validated = $request->validate([
            'type' => 'required|in:note,email,call,meeting,property_visit,offer',
            'description' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);
        
        $activity = new LeadActivity();
        $activity->lead_id = $lead->id;
        $activity->user_id = Auth::id();
        $activity->type = $validated['type'];
        $activity->description = $validated['description'];
        $activity->scheduled_at = $validated['scheduled_at'] ?? null;
        $activity->is_completed = $request->boolean('is_completed');
        $activity->save();
        
        // Si c'est un contact, mettre à jour la date de dernier contact
        if (in_array($validated['type'], ['email', 'call', 'meeting'])) {
            $lead->last_contacted_at = now();
            $lead->save();
        }
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Activité ajoutée avec succès.');
    }

    public function completeActivity(LeadActivity $activity)
    {
        $this->authorize('update', $activity->lead);
        
        $activity->is_completed = true;
        $activity->save();
        
        return redirect()->route('leads.show', $activity->lead)
            ->with('success', 'Activité marquée comme terminée.');
    }

    public function deleteActivity(LeadActivity $activity)
    {
        $this->authorize('update', $activity->lead);
        
        $lead = $activity->lead;
        $activity->delete();
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Activité supprimée avec succès.');
    }

    public function assign(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
        ]);
        
        $oldAgentId = $lead->agent_id;
        $lead->agent_id = $validated['agent_id'];
        $lead->save();
        
        // Créer une activité pour le changement d'agent
        $activity = new LeadActivity();
        $activity->lead_id = $lead->id;
        $activity->user_id = Auth::id();
        $activity->type = 'note';
        
        if ($oldAgentId) {
            $oldAgent = Agent::find($oldAgentId);
            $oldAgentName = $oldAgent ? $oldAgent->user->name : 'Inconnu';
            
            $newAgent = Agent::find($validated['agent_id']);
            $newAgentName = $newAgent ? $newAgent->user->name : 'Inconnu';
            $activity->description = "Agent modifié de '{$oldAgentName}' à '{$newAgentName}'";
        } else {
            $newAgent = Agent::find($validated['agent_id']);
            $newAgentName = $newAgent ? $newAgent->user->name : 'Inconnu';
            $activity->description = "Agent '{$newAgentName}' assigné";
        }
        
        $activity->save();
        
        // Notifier le nouvel agent
        $newAgent = Agent::find($validated['agent_id']);
        if ($newAgent && $newAgent->user) {
            $newAgent->user->notify(new LeadAssignedNotification($lead));
        }
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead assigné avec succès.');
    }

    public function changeStatus(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);
        
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,negotiation,converted,lost',
        ]);
        
        $oldStatus = $lead->status;
        $lead->status = $validated['status'];
        
        // Si le statut est passé à "converted", enregistrer la date de conversion
        if ($validated['status'] === 'converted') {
            $lead->converted_at = now();
        }
        
        // Si le lead est contacté, mettre à jour la date de dernier contact
        if ($validated['status'] === 'contacted') {
            $lead->last_contacted_at = now();
        }
        
        $lead->save();
        
        // Créer une activité pour le changement de statut
        $activity = new LeadActivity();
        $activity->lead_id = $lead->id;
        $activity->user_id = Auth::id();
        $activity->type = 'status_change';
        $activity->description = "Statut modifié de '{$oldStatus}' à '{$validated['status']}'";
        $activity->save();
        
        return redirect()->route('leads.show', $lead)
            ->with('success', 'Statut du lead modifié avec succès.');
    }
}