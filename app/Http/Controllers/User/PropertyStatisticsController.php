<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyStatisticsController extends Controller
{
    /**
     * Afficher les statistiques de visites pour une propriété.
     */
    public function show(Property $property)
    {
        // Vérifier que l'utilisateur a le droit de voir les statistiques
        $this->authorize('viewStatistics', $property);
        
        // Statistiques générales
        $totalVisits = $property->visits()->count();
        $completedVisits = $property->visits()->completed()->count();
        $cancelledVisits = $property->visits()->cancelled()->count();
        $pendingVisits = $property->visits()->pending()->count();
        $confirmedVisits = $property->visits()->confirmed()->count();
        
        // Statistiques par mois (6 derniers mois)
        $visitsByMonth = $this->getVisitsByMonth($property);
        
        // Taux de conversion (visites confirmées / demandes totales)
        $conversionRate = $totalVisits > 0 ? round(($completedVisits / $totalVisits) * 100, 2) : 0;
        
        // Taux d'annulation
        $cancellationRate = $totalVisits > 0 ? round(($cancelledVisits / $totalVisits) * 100, 2) : 0;
        
        // Jours les plus demandés
        $popularDays = $this->getPopularDays($property);
        
        // Créneaux horaires les plus demandés
        $popularTimeSlots = $this->getPopularTimeSlots($property);
        
        // Prochaines visites
        $upcomingVisits = $property->visits()
            ->with('visitor')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('visit_date', '>=', now())
            ->orderBy('visit_date')
            ->orderBy('visit_time_start')
            ->take(5)
            ->get();
        
        return view('properties.statistics', compact(
            'property',
            'totalVisits',
            'completedVisits',
            'cancelledVisits',
            'pendingVisits',
            'confirmedVisits',
            'visitsByMonth',
            'conversionRate',
            'cancellationRate',
            'popularDays',
            'popularTimeSlots',
            'upcomingVisits'
        ));
    }
    
    /**
     * Obtenir les statistiques de visites par mois.
     */
    private function getVisitsByMonth(Property $property)
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        
        $visits = $property->visits()
            ->select(
                DB::raw('YEAR(visit_date) as year'),
                DB::raw('MONTH(visit_date) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
            )
            ->where('visit_date', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Formater les données pour le graphique
        $months = [];
        $totals = [];
        $completed = [];
        $cancelled = [];
        
        // Créer un tableau pour les 6 derniers mois
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths(5 - $i)->startOfMonth();
            $monthKey = $date->format('Y-m');
            $months[] = $date->translatedFormat('M Y');
            $totals[$monthKey] = 0;
            $completed[$monthKey] = 0;
            $cancelled[$monthKey] = 0;
        }
        
        // Remplir avec les données réelles
        foreach ($visits as $visit) {
            $monthKey = $visit->year . '-' . str_pad($visit->month, 2, '0', STR_PAD_LEFT);
            $totals[$monthKey] = $visit->total;
            $completed[$monthKey] = $visit->completed;
            $cancelled[$monthKey] = $visit->cancelled;
        }
        
        return [
            'months' => $months,
            'totals' => array_values($totals),
            'completed' => array_values($completed),
            'cancelled' => array_values($cancelled),
        ];
    }
    
    /**
     * Obtenir les jours de la semaine les plus populaires pour les visites.
     */
    private function getPopularDays(Property $property)
    {
        $popularDays = $property->visits()
            ->select(DB::raw('DAYOFWEEK(visit_date) as day_of_week'), DB::raw('COUNT(*) as count'))
            ->groupBy('day_of_week')
            ->orderByDesc('count')
            ->get();
        
        // Formater les données
        $days = [
            1 => 'Dimanche',
            2 => 'Lundi',
            3 => 'Mardi',
            4 => 'Mercredi',
            5 => 'Jeudi',
            6 => 'Vendredi',
            7 => 'Samedi',
        ];
        
        $result = [];
        foreach ($popularDays as $day) {
            $result[] = [
                'day' => $days[$day->day_of_week],
                'count' => $day->count,
            ];
        }
        
        return $result;
    }
    
    /**
     * Obtenir les créneaux horaires les plus populaires pour les visites.
     */
    private function getPopularTimeSlots(Property $property)
    {
        $popularTimeSlots = $property->visits()
            ->select(DB::raw('HOUR(visit_time_start) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderByDesc('count')
            ->get();
        
        $result = [];
        foreach ($popularTimeSlots as $slot) {
            $result[] = [
                'hour' => sprintf('%02d:00', $slot->hour),
                'count' => $slot->count,
            ];
        }
        
        return $result;
    }
}
