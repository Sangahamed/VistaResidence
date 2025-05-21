<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PropertyVisit;
use App\Models\Property;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VisitRequestedNotification;
use App\Notifications\VisitConfirmedNotification;
use App\Notifications\VisitCancelledNotification;
use Illuminate\Support\Str;

class PropertyVisitController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = PropertyVisit::with(['property.owner', 'visitor'])
            ->where(function ($q) use ($user) {
                // Visites où l'utilisateur est visiteur
                $q->where('visitor_id', $user->id);

                // Ou visites où l'utilisateur est agent assigné
                $q->orWhere('agent_id', $user->id);

                // Ou visites pour les propriétés dont l'utilisateur est propriétaire
                $q->orWhereHas('property', function ($q) use ($user) {
                    $q->where('owner_id', $user->id);
                });

                // Pour les administrateurs de compagnie
                if ($user->isCompany()) {
                    $q->orWhereHas('property', function ($q) use ($user) {
                        $q->where('owner_id', $user->id)
                            ->orWhereHas('owner.company', function ($q) use ($user) {
                                $q->where('id', $user->company->id);
                            });
                    });
                }
            });

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('visit_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('visit_date', '<=', $request->date_to);
        }

        $visits = $query->latest('visit_date')->paginate(10);

        $statuses = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée'
        ];

        return view('properties.visits.index', compact('visits', 'statuses'));
    }

    public function create(Request $request)
    {
        $propertyId = $request->input('property_id');
        $property = $propertyId ? Property::findOrFail($propertyId) : null;

        $user = Auth::user();
        $agents = [];

        if ($user->isSuperAdmin()) {
            $agents = User::whereHas('agent')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $agents = User::whereHas('agent.agency', function ($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            })->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $agents = User::whereHas('agent', fn($q) => $q->where('agency_id', $agencyId))->get();
        } elseif ($user->isAgent()) {
            $agents = [$user];
        } else {
            $agents = User::whereHas('agent')->get();
        }

        return view('properties.visits.create', compact('property', 'agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time_start' => 'required|date_format:H:i',
            'visit_time_end' => 'required|date_format:H:i|after:visit_time_start',
            'notes' => 'nullable|string',
            'title' => 'required_if:property_id,null|string|max:255'
        ]);

        $property = $validated['property_id'] ? Property::with('owner')->find($validated['property_id']) : null;

        // Déterminer l'agent responsable selon le type de propriétaire
        $agentId = null;
        if ($property) {
            if ($property->owner->isIndividual()) {
                // Propriété appartenant à un particulier
                $agentId = $property->owner_id;
            } elseif ($property->owner->isCompany()) {
                // Propriété appartenant à une entreprise - prendre le premier agent de la compagnie
                $agentId = $property->owner->company->agents()->first()->id ?? null;
            }
        }

        $visit = PropertyVisit::create([
            'property_id' => $validated['property_id'] ?? null,
            'visitor_id' => Auth::id(),
            'agent_id' => $agentId,
            'visit_date' => $validated['visit_date'],
            'visit_time_start' => $validated['visit_time_start'],
            'visit_time_end' => $validated['visit_time_end'],
            'status' => $property ? 'pending' : 'confirmed',
            'notes' => $validated['notes'] ?? null,
            'title' => $validated['title'] ?? null,
            'is_private' => is_null($validated['property_id'] ?? null),
            'confirmation_code' => Str::random(8),
        ]);

        // Notifier le propriétaire/agent si visite liée à une propriété
        if ($property && $agentId) {
            $agent = User::find($agentId);
            $agent->notify(new VisitRequestedNotification($visit));
        }

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visite créée avec succès!');
    }


    public function show(PropertyVisit $visit)
    {
        // $this->authorize('view', $visit);

        $visit->load(['property', 'visitor', 'agent']);

        return view('properties.visits.show', compact('visit'));
    }

    public function edit(PropertyVisit $visit)
    {
        // $this->authorize('update', $visit);

        $properties = [];
        $agents = [];

        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 'active')->get();
            $agents = User::whereHas('agent')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $properties = Property::whereIn('company_id', $companyIds)->where('status', 'active')->get();
            $agents = User::whereHas('agent', function ($q) use ($companyIds) {
                $q->whereHas('agency', function ($q) use ($companyIds) {
                    $q->whereIn('company_id', $companyIds);
                });
            })->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $properties = Property::whereHas('agent', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->where('status', 'active')->get();
            $agents = User::whereHas('agent', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->get();
        } elseif ($user->isAgent()) {
            $properties = Property::where('agent_id', $user->agent->id)->where('status', 'active')->get();
            $agents = [Auth::user()];
        } else {
            // Client normal
            $properties = Property::where('status', 'active')->get();
            $agents = User::whereHas('agent', function ($q) use ($visit) {
                $q->whereHas('agency', function ($q) use ($visit) {
                    $q->where('id', $visit->property->agency_id);
                });
            })->get();
        }

        return view('visits.edit', compact('visit', 'properties', 'agents'));
    }

    public function update(Request $request, PropertyVisit $visit)
    {
        // $this->authorize('update', $visit);

        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'agent_id' => 'nullable|exists:users,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time_start' => 'required|date_format:H:i',
            'visit_time_end' => 'required|date_format:H:i|after:visit_time_start',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que l'utilisateur a le droit de modifier cette visite
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->isCompanyAdmin() && !Auth::user()->isAgencyAdmin()) {
            if (Auth::user()->isAgent() && $visit->agent_id !== Auth::id()) {
                return redirect()->back()
                    ->withErrors(['agent_id' => 'Vous n\'avez pas le droit de modifier cette visite.'])
                    ->withInput();
            } elseif (!Auth::user()->isAgent() && $visit->visitor_id !== Auth::id()) {
                return redirect()->back()
                    ->withErrors(['visitor_id' => 'Vous n\'avez pas le droit de modifier cette visite.'])
                    ->withInput();
            }
        }

        $oldAgentId = $visit->agent_id;

        $visit->property_id = $validated['property_id'];
        $visit->agent_id = $validated['agent_id'] ?? null;
        $visit->visit_date = $validated['visit_date'];
        $visit->visit_time_start = $validated['visit_time_start'];
        $visit->visit_time_end = $validated['visit_time_end'];
        $visit->notes = $validated['notes'] ?? null;
        $visit->save();

        // Notifier le nouvel agent si changé
        if ($visit->agent_id && $visit->agent_id !== $oldAgentId) {
            $agent = User::find($visit->agent_id);
            $agent->notify(new VisitRequestedNotification($visit));
        }

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visite mise à jour avec succès.');
    }

    public function destroy(PropertyVisit $visit)
    {
        // $this->authorize('delete', $visit);

        $visit->delete();

        return redirect()->route('visits.index')
            ->with('success', 'Visite supprimée avec succès.');
    }

    public function confirm(PropertyVisit $visit)
    {
        // Seul le propriétaire/agent peut confirmer
        $user = Auth::user();
        $canConfirm = $user->id === $visit->agent_id
            || ($visit->property && $user->id === $visit->property->user_id);

        if (!$canConfirm) {
            abort(403, 'Unauthorized action.');
        }

        $visit->status = 'confirmed';
        $visit->save();

        // Notifier le visiteur
        $visit->visitor->notify(new VisitConfirmedNotification($visit));

        return redirect()->back()->with('success', 'Visite confirmée!');
    }

    public function complete(PropertyVisit $visit)
    {
        // $this->authorize('complete', $visit);

        if ($visit->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être marquée comme terminée car elle n\'est pas confirmée.');
        }

        $visit->status = 'completed';
        $visit->save();

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visite marquée comme terminée avec succès.');
    }

       public function cancelForm(PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur a le droit d'annuler cette visite
        // $this->authorize('cancel', $visit);
        
        if ($visit->isCancelled() || $visit->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être annulée car elle est déjà terminée ou annulée.');
        }
        
        return view('properties.visits.cancel', compact('visit'));
    }

    public function cancel(Request $request, PropertyVisit $visit)
    {
        // $this->authorize('cancel', $visit);

        if ($visit->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être annulée car elle est déjà terminée.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        $visit->status = 'cancelled';
        $visit->cancellation_reason = $validated['cancellation_reason'];
        $visit->cancelled_by = Auth::id();
        $visit->save();

        // Notifier l'autre partie
        if ($visit->visitor_id === Auth::id() && $visit->agent_id) {
            $agent = User::find($visit->agent_id);
            $agent->notify(new VisitCancelledNotification($visit));
        } elseif ($visit->agent_id === Auth::id() || Auth::user()->isAgencyAdmin() || Auth::user()->isCompanyAdmin() || Auth::user()->isSuperAdmin()) {
            $visitor = User::find($visit->visitor_id);
            $visitor->notify(new VisitCancelledNotification($visit));
        }

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visite annulée avec succès.');
    }

    public function addNote(Request $request, PropertyVisit $visit)
    {
        // $this->authorize('update', $visit);

        $validated = $request->validate([
            'visitor_notes' => 'required|string',
        ]);

        $visit->visitor_notes = $validated['visitor_notes'];
        $visit->save();

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Note ajoutée avec succès.');
    }

    public function updateStatus(PropertyVisit $visit, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        // Vérifier les permissions
        $user = Auth::user();
        $canUpdate = $user->id === $visit->agent_id
            || ($visit->property && $user->id === $visit->property->owner_id)
            || ($user->isCompany() && $visit->property && $visit->property->owner->company_id === $user->company_id);

        if (!$canUpdate) {
            abort(403, 'Unauthorized action.');
        }

        $visit->update(['status' => $validated['status']]);

        // Envoyer les notifications si nécessaire
        if ($validated['status'] === 'confirmed') {
            $visit->visitor->notify(new VisitConfirmedNotification($visit));
        } elseif ($validated['status'] === 'cancelled') {
            $visit->visitor->notify(new VisitCancelledNotification($visit));
        }

        return back()->with('success', 'Statut mis à jour avec succès');
    }

    public function calendar()
    {
        return view('properties.visits.calendar', [
            'canCreate' => true // Forcer l'accès
        ]);
    }

    public function calendarEvents(Request $request)
    {
        $user = $request->user();

        $query = PropertyVisit::with(['property.owner', 'visitor'])
            ->where(function ($q) use ($user) {
                // Visites où l'utilisateur est visiteur
                $q->where('visitor_id', $user->id);

                // Ou visites où l'utilisateur est agent assigné
                $q->orWhere('agent_id', $user->id);

                // Ou visites pour les propriétés dont l'utilisateur est propriétaire
                $q->orWhereHas('property', function ($q) use ($user) {
                    $q->where('owner_id', $user->id);
                });

                // Pour les administrateurs de compagnie
                if ($user->isCompany()) {
                    $q->orWhereHas('property', function ($q) use ($user) {
                        $q->where('owner_id', $user->id)
                            ->orWhereHas('owner.company', function ($q) use ($user) {
                                $q->where('id', $user->company->id);
                            });
                    });
                }
            });

        return response()->json(
            $query->get()->map(function ($visit) {
                // Couleurs selon le statut (utilisant les classes Tailwind)
                $statusColors = [
                    'pending' => ['bg-amber-500', 'border-amber-600'],
                    'confirmed' => ['bg-green-500', 'border-green-600'],
                    'completed' => ['bg-indigo-500', 'border-indigo-600'],
                    'cancelled' => ['bg-gray-500', 'border-gray-600']
                ];

                $colors = $visit->is_private
                    ? ['bg-orange-500', 'border-orange-600']
                    : ($statusColors[$visit->status] ?? $statusColors['pending']);

                return [
                    'id' => $visit->id,
                    'title' => $visit->is_private
                        ? $visit->title
                        : ($visit->property->title ?? 'N/A'),
                    'start' => $visit->visit_date->toDateString() . 'T' . $visit->visit_time_start->format('H:i:s'),
                    'end' => $visit->visit_date->toDateString() . 'T' . $visit->visit_time_end->format('H:i:s'),


                    'backgroundColor' => $colors[0],
                    'borderColor' => $colors[1],
                    'textColor' => 'text-white',
                    'extendedProps' => [
                        'status' => $visit->status,
                        'is_private' => $visit->is_private,
                        'type' => $visit->is_private ? 'private' : 'property',
                        'agent_id' => $visit->agent_id,
                        'property_owner_id' => $visit->property->owner_id ?? null
                    ]
                ];
            })
        );
    }
}
