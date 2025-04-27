<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Models\User;
use App\Notifications\VisitRequestedNotification;
use App\Notifications\VisitConfirmedNotification;
use App\Notifications\VisitCancelledNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PropertyVisitController extends Controller
{
    /**
     * Afficher le formulaire de demande de visite.
     */
    public function create(Property $property)
    {
        // Vérifier si la propriété est disponible pour des visites
        if ($property->status !== 'for_sale' && $property->status !== 'for_rent') {
            return redirect()->route('properties.show', $property)
                ->with('error', 'Cette propriété n\'est pas disponible pour des visites.');
        }

        // Générer les dates disponibles (prochains 14 jours)
        $availableDates = [];
        $startDate = Carbon::tomorrow();
        $endDate = Carbon::tomorrow()->addDays(14);

        for ($date = $startDate; $date->lte($endDate); $date = $date->copy()->addDay()) {
            // Exclure les dimanches
            if ($date->dayOfWeek !== Carbon::SUNDAY) {
                $availableDates[] = [
                    'date' => $date->format('Y-m-d'),
                    'formatted' => $date->translatedFormat('l j F Y'),
                    'slots' => $property->getAvailableTimeSlots($date)
                ];
            }
        }

        // Filtrer les dates qui n'ont pas de créneaux disponibles
        $availableDates = array_filter($availableDates, function ($date) {
            return count($date['slots']) > 0;
        });

        return view('properties.visits.create', compact('property', 'availableDates'));
    }

    /**
     * Enregistrer une nouvelle demande de visite.
     */
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour demander une visite.');
        }

        // Extraire l'heure de début et de fin du créneau sélectionné
        list($timeStart, $timeEnd) = explode(' - ', $request->visit_time);

        // Vérifier si le créneau est toujours disponible
        $selectedDate = Carbon::parse($request->visit_date);
        $availableSlots = $property->getAvailableTimeSlots($selectedDate);
        
        $slotExists = false;
        foreach ($availableSlots as $slot) {
            if ($slot['formatted'] === $request->visit_time) {
                $slotExists = true;
                break;
            }
        }

        if (!$slotExists) {
            return redirect()->back()
                ->with('error', 'Ce créneau n\'est plus disponible. Veuillez en choisir un autre.')
                ->withInput();
        }

        // Créer la demande de visite
        $visit = PropertyVisit::create([
            'property_id' => $property->id,
            'visitor_id' => auth()->id(),
            'agent_id' => $property->agent_id, // Assigner automatiquement à l'agent de la propriété
            'visit_date' => $request->visit_date,
            'visit_time_start' => $timeStart,
            'visit_time_end' => $timeEnd,
            'status' => 'pending',
            'visitor_notes' => $request->notes,
            'confirmation_code' => Str::random(8),
        ]);

        // Notifier l'agent
        if ($property->agent) {
            $property->agent->notify(new VisitRequestedNotification($visit));
        }

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Votre demande de visite a été enregistrée. Vous recevrez une confirmation prochainement.');
    }

    /**
     * Afficher les détails d'une visite.
     */
    public function show(PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur a le droit de voir cette visite
        $this->authorize('view', $visit);

        return view('properties.visits.show', compact('visit'));
    }

    /**
     * Afficher la liste des visites de l'utilisateur.
     */
    public function index()
    {
        $user = auth()->user();
        
        $upcomingVisits = $user->requestedVisits()
            ->with('property')
            ->upcoming()
            ->orderBy('visit_date')
            ->orderBy('visit_time_start')
            ->get();
            
        $pastVisits = $user->requestedVisits()
            ->with('property')
            ->past()
            ->orderByDesc('visit_date')
            ->orderByDesc('visit_time_start')
            ->get();

        return view('properties.visits.index', compact('upcomingVisits', 'pastVisits'));
    }

    /**
     * Afficher la liste des visites assignées à l'agent.
     */
    public function agentIndex()
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un agent
        if (!$user->hasRole('agent') && !$user->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous n\'avez pas accès à cette page.');
        }
        
        $pendingVisits = $user->assignedVisits()
            ->with('property', 'visitor')
            ->pending()
            ->orderBy('visit_date')
            ->orderBy('visit_time_start')
            ->get();
            
        $confirmedVisits = $user->assignedVisits()
            ->with('property', 'visitor')
            ->confirmed()
            ->upcoming()
            ->orderBy('visit_date')
            ->orderBy('visit_time_start')
            ->get();
            
        $pastVisits = $user->assignedVisits()
            ->with('property', 'visitor')
            ->whereIn('status', ['confirmed', 'completed'])
            ->past()
            ->orderByDesc('visit_date')
            ->orderByDesc('visit_time_start')
            ->get();

        return view('properties.visits.agent-index', compact('pendingVisits', 'confirmedVisits', 'pastVisits'));
    }

    /**
     * Confirmer une visite (par l'agent).
     */
    public function confirm(PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur est l'agent assigné à cette visite
        $this->authorize('update', $visit);
        
        if (!$visit->isPending()) {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être confirmée car elle n\'est pas en attente.');
        }
        
        $visit->update([
            'status' => 'confirmed',
        ]);
        
        // Notifier le visiteur
        $visit->visitor->notify(new VisitConfirmedNotification($visit));
        
        return redirect()->route('agent.visits.index')
            ->with('success', 'La visite a été confirmée avec succès.');
    }

    /**
     * Marquer une visite comme terminée (par l'agent).
     */
    public function complete(PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur est l'agent assigné à cette visite
        $this->authorize('update', $visit);
        
        if (!$visit->isConfirmed()) {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être marquée comme terminée car elle n\'est pas confirmée.');
        }
        
        $visit->update([
            'status' => 'completed',
        ]);
        
        return redirect()->route('agent.visits.index')
            ->with('success', 'La visite a été marquée comme terminée avec succès.');
    }

    /**
     * Afficher le formulaire d'annulation d'une visite.
     */
    public function cancelForm(PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur a le droit d'annuler cette visite
        $this->authorize('cancel', $visit);
        
        if ($visit->isCancelled() || $visit->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être annulée car elle est déjà terminée ou annulée.');
        }
        
        return view('properties.visits.cancel', compact('visit'));
    }

    /**
     * Annuler une visite.
     */
    public function cancel(Request $request, PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur a le droit d'annuler cette visite
        $this->authorize('cancel', $visit);
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);
        
        if ($visit->isCancelled() || $visit->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Cette visite ne peut pas être annulée car elle est déjà terminée ou annulée.');
        }
        
        $visit->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => auth()->id(),
        ]);
        
        // Notifier l'autre partie (agent ou visiteur)
        $notifyUser = auth()->id() === $visit->visitor_id ? $visit->agent : $visit->visitor;
        if ($notifyUser) {
            $notifyUser->notify(new VisitCancelledNotification($visit));
        }
        
        return redirect()->route(auth()->user()->hasRole('agent') ? 'agent.visits.index' : 'visits.index')
            ->with('success', 'La visite a été annulée avec succès.');
    }

    /**
     * Réassigner une visite à un autre agent (admin uniquement).
     */
    public function reassign(Request $request, PropertyVisit $visit)
    {
        // Vérifier que l'utilisateur est un administrateur
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()
                ->with('error', 'Vous n\'avez pas les droits pour réassigner cette visite.');
        }
        
        $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);
        
        // Vérifier que le nouvel agent est bien un agent
        $newAgent = User::find($request->agent_id);
        if (!$newAgent->hasRole('agent')) {
            return redirect()->back()
                ->with('error', 'L\'utilisateur sélectionné n\'est pas un agent.');
        }
        
        $visit->update([
            'agent_id' => $request->agent_id,
        ]);
        
        // Notifier le nouvel agent
        $newAgent->notify(new VisitRequestedNotification($visit));
        
        return redirect()->back()
            ->with('success', 'La visite a été réassignée avec succès.');
    }
}
