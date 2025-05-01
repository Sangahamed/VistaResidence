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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PropertyVisit::query();
        
        // Filtres
        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->where('visit_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where('visit_date', '<=', $request->date_to);
        }
        
        // Filtrer selon le rôle de l'utilisateur
        $user = Auth::user();
        
        if ($user->isAgent()) {
            $query->where('agent_id', $user->id);
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $query->whereHas('agent', function($q) use ($agencyId) {
                $q->whereHas('agency', function($q) use ($agencyId) {
                    $q->where('id', $agencyId);
                });
            });
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $query->whereHas('property', function($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            });
        } elseif (!$user->isSuperAdmin()) {
            // Client normal
            $query->where('visitor_id', $user->id);
        }
        
        // Tri
        $sortBy = $request->input('sort_by', 'visit_date');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);
        
        $visits = $query->with(['property', 'visitor', 'agent'])->paginate(15);
        
        // Données pour les filtres
        $properties = [];
        $statuses = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée'
        ];
        
        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 'active')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $properties = Property::whereIn('company_id', $companyIds)->where('status', 'active')->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $properties = Property::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->where('status', 'active')->get();
        } elseif ($user->isAgent()) {
            $properties = Property::where('agent_id', $user->agent->id)->where('status', 'active')->get();
        }
        
        return view('visits.index', compact('visits', 'properties', 'statuses'));
    }

    public function create(Request $request)
    {
        $propertyId = $request->input('property_id');
        $property = null;
        
        if ($propertyId) {
            $property = Property::findOrFail($propertyId);
        }
        
        $properties = [];
        $agents = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 'active')->get();
            $agents = User::whereHas('agent')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $properties = Property::whereIn('company_id', $companyIds)->where('status', 'active')->get();
            $agents = User::whereHas('agent', function($q) use ($companyIds) {
                $q->whereHas('agency', function($q) use ($companyIds) {
                    $q->whereIn('company_id', $companyIds);
                });
            })->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $properties = Property::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->where('status', 'active')->get();
            $agents = User::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->get();
        } elseif ($user->isAgent()) {
            $properties = Property::where('agent_id', $user->agent->id)->where('status', 'active')->get();
            $agents = [Auth::user()];
        } else {
            // Client normal
            $properties = Property::where('status', 'active')->get();
            
            if ($property) {
                $agents = User::whereHas('agent', function($q) use ($property) {
                    $q->whereHas('agency', function($q) use ($property) {
                        $q->where('id', $property->agency_id);
                    });
                })->get();
            } else {
                $agents = User::whereHas('agent')->get();
            }
        }
        
        return view('visits.create', compact('property', 'properties', 'agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'agent_id' => 'nullable|exists:users,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time_start' => 'required|date_format:H:i',
            'visit_time_end' => 'required|date_format:H:i|after:visit_time_start',
            'notes' => 'nullable|string',
        ]);
        
        $property = Property::findOrFail($validated['property_id']);
        
        // Si aucun agent n'est spécifié, assigner automatiquement l'agent de la propriété
        if (!$request->has('agent_id') && $property->agent_id) {
            $validated['agent_id'] = $property->agent_id;
        }
        
        $visit = new PropertyVisit();
        $visit->property_id = $validated['property_id'];
        $visit->visitor_id = Auth::id();
        $visit->agent_id = $validated['agent_id'] ?? null;
        $visit->visit_date = $validated['visit_date'];
        $visit->visit_time_start = $validated['visit_time_start'];
        $visit->visit_time_end = $validated['visit_time_end'];
        $visit->status = 'pending';
        $visit->notes = $validated['notes'] ?? null;
        $visit->confirmation_code = Str::random(8);
        $visit->save();
        
        // Notifier l'agent
        if ($visit->agent_id) {
            $agent = User::find($visit->agent_id);
            $agent->notify(new VisitRequestedNotification($visit));
        }
        
        return redirect()->route('visits.show', $visit)
            ->with('success', 'Demande de visite créée avec succès.');
    }

    public function show(PropertyVisit $visit)
    {
        $this->authorize('view', $visit);
        
        $visit->load(['property', 'visitor', 'agent']);
        
        return view('visits.show', compact('visit'));
    }

    public function edit(PropertyVisit $visit)
    {
        $this->authorize('update', $visit);
        
        $properties = [];
        $agents = [];
        
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 'active')->get();
            $agents = User::whereHas('agent')->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $properties = Property::whereIn('company_id', $companyIds)->where('status', 'active')->get();
            $agents = User::whereHas('agent', function($q) use ($companyIds) {
                $q->whereHas('agency', function($q) use ($companyIds) {
                    $q->whereIn('company_id', $companyIds);
                });
            })->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $properties = Property::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->where('status', 'active')->get();
            $agents = User::whereHas('agent', function($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })->get();
        } elseif ($user->isAgent()) {
            $properties = Property::where('agent_id', $user->agent->id)->where('status', 'active')->get();
            $agents = [Auth::user()];
        } else {
            // Client normal
            $properties = Property::where('status', 'active')->get();
            $agents = User::whereHas('agent', function($q) use ($visit) {
                $q->whereHas('agency', function($q) use ($visit) {
                    $q->where('id', $visit->property->agency_id);
                });
            })->get();
        }
        
        return view('visits.edit', compact('visit', 'properties', 'agents'));
    }

    public function update(Request $request, PropertyVisit $visit)
    {
        $this->authorize('update', $visit);
        
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
        $this->authorize('delete', $visit);
        
        $visit->delete();
        
        return redirect()->route('visits.index')
            ->with('success', 'Visite supprimée avec succès.');
    }

    public function confirm(PropertyVisit $visit)
    {
        $this->authorize('confirm', $visit);
        
        if ($visit->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être confirmée car elle n\'est pas en attente.');
        }
        
        $visit->status = 'confirmed';
        $visit->save();
        
        // Notifier le visiteur
        $visitor = User::find($visit->visitor_id);
        $visitor->notify(new VisitConfirmedNotification($visit));
        
        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visite confirmée avec succès.');
    }

    public function complete(PropertyVisit $visit)
    {
        $this->authorize('complete', $visit);
        
        if ($visit->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être marquée comme terminée car elle n\'est pas confirmée.');
        }
        
        $visit->status = 'completed';
        $visit->save();
        
        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visite marquée comme terminée avec succès.');
    }

    public function cancel(Request $request, PropertyVisit $visit)
    {
        $this->authorize('cancel', $visit);
        
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
        $this->authorize('update', $visit);
        
        $validated = $request->validate([
            'visitor_notes' => 'required|string',
        ]);
        
        $visit->visitor_notes = $validated['visitor_notes'];
        $visit->save();
        
        return redirect()->route('visits.show', $visit)
            ->with('success', 'Note ajoutée avec succès.');
    }

    public function calendar()
    {
        $user = Auth::user();
        
        $visits = [];
        
        if ($user->isSuperAdmin()) {
            $visits = PropertyVisit::with(['property', 'visitor', 'agent'])->get();
        } elseif ($user->isCompanyAdmin()) {
            $companyIds = $user->companies()->wherePivot('is_admin', true)->pluck('companies.id');
            $visits = PropertyVisit::with(['property', 'visitor', 'agent'])
                ->whereHas('property', function($q) use ($companyIds) {
                    $q->whereIn('company_id', $companyIds);
                })->get();
        } elseif ($user->isAgencyAdmin()) {
            $agencyId = $user->agent->agency_id;
            $visits = PropertyVisit::with(['property', 'visitor', 'agent'])
                ->whereHas('agent', function($q) use ($agencyId) {
                    $q->whereHas('agency', function($q) use ($agencyId) {
                        $q->where('id', $agencyId);
                    });
                })->get();
        } elseif ($user->isAgent()) {
            $visits = PropertyVisit::with(['property', 'visitor'])
                ->where('agent_id', $user->id)
                ->get();
        } else {
            // Client normal
            $visits = PropertyVisit::with(['property', 'agent'])
                ->where('visitor_id', $user->id)
                ->get();
        }
        
        return view('visits.calendar', compact('visits'));
    }
}
