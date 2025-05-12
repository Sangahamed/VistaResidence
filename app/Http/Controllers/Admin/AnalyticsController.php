<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Property;
use App\Models\PropertyVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAnalytics', User::class);
        
        // Statistiques générales
        $stats = [
            'total_properties' => Property::count(),
            'available_properties' => Property::where('status', 'available')->count(),
            'total_leads' => Lead::count(),
            'total_visits' => PropertyVisit::count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];
        
        // Tendances des propriétés consultées (30 derniers jours)
        $propertyViewTrend = $this->getPropertyViewTrend();
        
        // Répartition des leads par source
        $leadsBySource = $this->getLeadsBySource();
        
        // Répartition des propriétés par ville
        $propertiesByCity = $this->getPropertiesByCity();
        
        // Activité des utilisateurs
        $userActivity = $this->getUserActivity();
        
        return view('admin.analytics.index', compact(
            'stats',
            'propertyViewTrend',
            'leadsBySource',
            'propertiesByCity',
            'userActivity'
        ));
    }
    
    private function calculateConversionRate()
    {
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        
        if ($totalLeads === 0) {
            return 0;
        }
        
        return round(($convertedLeads / $totalLeads) * 100, 2);
    }
    
    private function getPropertyViewTrend()
    {
        $startDate = Carbon::now()->subDays(30);
        
        return ActivityLog::where('action', 'property_viewed')
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                ];
            });
    }
    
    private function getLeadsBySource()
    {
        return Lead::select('source', DB::raw('COUNT(*) as count'))
            ->groupBy('source')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) {
                return [
                    'source' => $item->source ?: 'Non spécifié',
                    'count' => $item->count,
                ];
            });
    }
    
    private function getPropertiesByCity()
    {
        return Property::select('city', DB::raw('COUNT(*) as count'))
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'city' => $item->city,
                    'count' => $item->count,
                ];
            });
    }
    
    private function getUserActivity()
    {
        $startDate = Carbon::now()->subDays(7);
        
        return ActivityLog::with('user')
            ->where('created_at', '>=', $startDate)
            ->select('user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'user' => $item->user ? $item->user->name : 'Anonyme',
                    'count' => $item->count,
                ];
            });
    }
}