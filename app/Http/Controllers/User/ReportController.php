<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Déterminer l'agence selon le rôle de l'utilisateur
        $agencyId = null;
        
        if ($user->role === 'admin') {
            // Admin peut voir toutes les agences
            $agencies = Agency::all();
            $selectedAgency = $agencies->first();
            if ($selectedAgency) {
                $agencyId = $selectedAgency->id;
            }
        } elseif ($user->role === 'agency_admin') {
            // Agency admin voit son agence
            $agent = Agent::where('user_id', $user->id)->first();
            if ($agent && $agent->agency_id) {
                $agencyId = $agent->agency_id;
                $selectedAgency = Agency::find($agencyId);
                $agencies = collect([$selectedAgency]);
            } else {
                return redirect()->route('home')->with('error', 'Vous n\'êtes pas associé à une agence.');
            }
        } else {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }
        
        // Période par défaut: 30 derniers jours
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // Récupérer les statistiques de l'agence
        $stats = $this->getAgencyStats($agencyId, $startDate, $endDate);
        
        return view('reports.index', compact(
            'agencies',
            'selectedAgency',
            'startDate',
            'endDate',
            'stats'
        ));
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:agency,agents,properties,leads,marketing',
        ]);
        
        $agencyId = $request->agency_id;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $reportType = $request->report_type;
        
        $selectedAgency = Agency::findOrFail($agencyId);
        
        // Récupérer les agences selon le rôle
        $user = Auth::user();
        if ($user->role === 'admin') {
            $agencies = Agency::all();
        } else {
            $agencies = collect([$selectedAgency]);
        }
        
        // Récupérer les statistiques selon le type de rapport
        switch ($reportType) {
            // case 'agency':
            //     $stats = $this->getAgencyStats($agencyId, $startDate, $endDate);
            case 'agency':
                $stats = $this->getAgencyStats($agencyId, $startDate, $endDate);
                break;
            case 'agents':
                $stats = $this->getAgentsStats($agencyId, $startDate, $endDate);
                break;
            case 'properties':
                $stats = $this->getPropertiesStats($agencyId, $startDate, $endDate);
                break;
            case 'leads':
                $stats = $this->getLeadsStats($agencyId, $startDate, $endDate);
                break;
            case 'marketing':
                $stats = $this->getMarketingStats($agencyId, $startDate, $endDate);
                break;
            default:
                $stats = $this->getAgencyStats($agencyId, $startDate, $endDate);
                break;
        }
        
        return view('reports.show', compact(
            'agencies',
            'selectedAgency',
            'startDate',
            'endDate',
            'reportType',
            'stats'
        ));
    }
    
    public function export(Request $request)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:agency,agents,properties,leads,marketing',
            'format' => 'required|in:pdf,csv,excel',
        ]);
        
        $agencyId = $request->agency_id;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $reportType = $request->report_type;
        $format = $request->format;
        
        // Récupérer les statistiques selon le type de rapport
        switch ($reportType) {
            case 'agency':
                $stats = $this->getAgencyStats($agencyId, $startDate, $endDate);
                break;
            case 'agents':
                $stats = $this->getAgentsStats($agencyId, $startDate, $endDate);
                break;
            case 'properties':
                $stats = $this->getPropertiesStats($agencyId, $startDate, $endDate);
                break;
            case 'leads':
                $stats = $this->getLeadsStats($agencyId, $startDate, $endDate);
                break;
            case 'marketing':
                $stats = $this->getMarketingStats($agencyId, $startDate, $endDate);
                break;
            default:
                $stats = $this->getAgencyStats($agencyId, $startDate, $endDate);
                break;
        }
        
        $agency = Agency::findOrFail($agencyId);
        
        // Générer le rapport selon le format demandé
        switch ($format) {
            case 'pdf':
                return $this->generatePDF($agency, $reportType, $startDate, $endDate, $stats);
            case 'csv':
                return $this->generateCSV($agency, $reportType, $startDate, $endDate, $stats);
            case 'excel':
                return $this->generateExcel($agency, $reportType, $startDate, $endDate, $stats);
            default:
                return redirect()->back()->with('error', 'Format non pris en charge.');
        }
    }
    
    private function getAgencyStats($agencyId, $startDate, $endDate)
    {
        $agency = Agency::findOrFail($agencyId);
        
        // Statistiques générales
        $totalAgents = Agent::where('agency_id', $agencyId)->count();
        $activeAgents = Agent::where('agency_id', $agencyId)->where('is_active', true)->count();
        
        $totalProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })->count();
        
        $activeListings = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })->where('status', 'active')->count();
        
        $soldProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->count();
        
        $totalSalesValue = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->sum('price');
        
        // Leads et conversions
        $totalLeads = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();
        
        $convertedLeads = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'converted')
        ->whereBetween('converted_at', [$startDate, $endDate])
        ->count();
        
        $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;
        
        // Ventes mensuelles
        $monthlySales = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->selectRaw('MONTH(sold_at) as month, YEAR(sold_at) as year, COUNT(*) as count, SUM(price) as total')
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        // Formater les données pour les graphiques
        $salesChartData = [];
        $revenueChartData = [];
        
        // Créer un tableau de tous les mois dans la période
        $period = Carbon::parse($startDate)->monthsUntil($endDate);
        foreach ($period as $date) {
            $month = $date->month;
            $year = $date->year;
            
            $monthlySale = $monthlySales->first(function ($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });
            
            $salesChartData[] = [
                'month' => $date->format('M Y'),
                'count' => $monthlySale ? $monthlySale->count : 0
            ];
            
            $revenueChartData[] = [
                'month' => $date->format('M Y'),
                'total' => $monthlySale ? $monthlySale->total : 0
            ];
        }
        
        // Marketing
        $marketingCampaigns = MarketingCampaign::where('agency_id', $agencyId)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhere(function ($query) use ($agencyId, $startDate, $endDate) {
                $query->where('agency_id', $agencyId)
                    ->where('start_date', '<', $startDate)
                    ->where(function ($q) use ($endDate) {
                        $q->where('end_date', '>=', $endDate)
                            ->orWhereNull('end_date');
                    });
            })
            ->count();
        
        $marketingCost = MarketingCampaign::where('agency_id', $agencyId)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhere(function ($query) use ($agencyId, $startDate, $endDate) {
                $query->where('agency_id', $agencyId)
                    ->where('start_date', '<', $startDate)
                    ->where(function ($q) use ($endDate) {
                        $q->where('end_date', '>=', $endDate)
                            ->orWhereNull('end_date');
                    });
            })
            ->sum('cost');
        
        return [
            'totalAgents' => $totalAgents,
            'activeAgents' => $activeAgents,
            'totalProperties' => $totalProperties,
            'activeListings' => $activeListings,
            'soldProperties' => $soldProperties,
            'totalSalesValue' => $totalSalesValue,
            'totalLeads' => $totalLeads,
            'convertedLeads' => $convertedLeads,
            'conversionRate' => $conversionRate,
            'salesChartData' => $salesChartData,
            'revenueChartData' => $revenueChartData,
            'marketingCampaigns' => $marketingCampaigns,
            'marketingCost' => $marketingCost,
        ];
    }
    
    private function getAgentsStats($agencyId, $startDate, $endDate)
    {
        // Récupérer tous les agents de l'agence
        $agents = Agent::where('agency_id', $agencyId)
            ->with('user')
            ->get();
        
        $agentsStats = [];
        
        foreach ($agents as $agent) {
            // Propriétés
            $totalProperties = Property::where('agent_id', $agent->id)->count();
            
            $activeListings = Property::where('agent_id', $agent->id)
                ->where('status', 'active')
                ->count();
            
            $soldProperties = Property::where('agent_id', $agent->id)
                ->where('status', 'sold')
                ->whereBetween('sold_at', [$startDate, $endDate])
                ->count();
            
            $totalSalesValue = Property::where('agent_id', $agent->id)
                ->where('status', 'sold')
                ->whereBetween('sold_at', [$startDate, $endDate])
                ->sum('price');
            
            // Leads
            $totalLeads = Lead::where('agent_id', $agent->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            
            $convertedLeads = Lead::where('agent_id', $agent->id)
                ->where('status', 'converted')
                ->whereBetween('converted_at', [$startDate, $endDate])
                ->count();
            
            $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;
            
            // Ventes mensuelles
            $monthlySales = Property::where('agent_id', $agent->id)
                ->where('status', 'sold')
                ->whereBetween('sold_at', [$startDate, $endDate])
                ->selectRaw('MONTH(sold_at) as month, YEAR(sold_at) as year, COUNT(*) as count, SUM(price) as total')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            
            // Formater les données pour les graphiques
            $salesChartData = [];
            
            // Créer un tableau de tous les mois dans la période
            $period = Carbon::parse($startDate)->monthsUntil($endDate);
            foreach ($period as $date) {
                $month = $date->month;
                $year = $date->year;
                
                $monthlySale = $monthlySales->first(function ($item) use ($month, $year) {
                    return $item->month == $month && $item->year == $year;
                });
                
                $salesChartData[] = [
                    'month' => $date->format('M Y'),
                    'count' => $monthlySale ? $monthlySale->count : 0,
                    'total' => $monthlySale ? $monthlySale->total : 0
                ];
            }
            
            $agentsStats[] = [
                'agent' => $agent,
                'totalProperties' => $totalProperties,
                'activeListings' => $activeListings,
                'soldProperties' => $soldProperties,
                'totalSalesValue' => $totalSalesValue,
                'totalLeads' => $totalLeads,
                'convertedLeads' => $convertedLeads,
                'conversionRate' => $conversionRate,
                'salesChartData' => $salesChartData,
            ];
        }
        
        // Trier les agents par nombre de ventes
        usort($agentsStats, function ($a, $b) {
            return $b['soldProperties'] - $a['soldProperties'];
        });
        
        return $agentsStats;
    }
    
    private function getPropertiesStats($agencyId, $startDate, $endDate)
    {
        // Statistiques générales des propriétés
        $totalProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })->count();
        
        $activeListings = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })->where('status', 'active')->count();
        
        $soldProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->count();
        
        $pendingProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })->where('status', 'pending')->count();
        
        // Prix moyen
        $averagePrice = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })->avg('price');
        
        $averageSoldPrice = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->avg('price');
        
        // Durée moyenne de vente
        $avgDaysOnMarket = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->whereNotNull('created_at')
        ->get()
        ->avg(function ($property) {
            return $property->created_at->diffInDays($property->sold_at);
        });
        
        // Répartition par type de propriété
        $propertiesByType = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->select('type', DB::raw('count(*) as count'))
        ->groupBy('type')
        ->get();
        
        // Répartition par nombre de chambres
        $propertiesByBedrooms = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->select('bedrooms', DB::raw('count(*) as count'))
        ->groupBy('bedrooms')
        ->orderBy('bedrooms')
        ->get();
        
        // Propriétés les plus vues
        $mostViewedProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->orderBy('views', 'desc')
        ->take(10)
        ->get();
        
        // Propriétés récemment vendues
        $recentlySoldProperties = Property::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'sold')
        ->whereBetween('sold_at', [$startDate, $endDate])
        ->orderBy('sold_at', 'desc')
        ->take(10)
        ->get();
        
        return [
            'totalProperties' => $totalProperties,
            'activeListings' => $activeListings,
            'soldProperties' => $soldProperties,
            'pendingProperties' => $pendingProperties,
            'averagePrice' => $averagePrice,
            'averageSoldPrice' => $averageSoldPrice,
            'avgDaysOnMarket' => $avgDaysOnMarket,
            'propertiesByType' => $propertiesByType,
            'propertiesByBedrooms' => $propertiesByBedrooms,
            'mostViewedProperties' => $mostViewedProperties,
            'recentlySoldProperties' => $recentlySoldProperties,
        ];
    }
    
    private function getLeadsStats($agencyId, $startDate, $endDate)
    {
        // Statistiques générales des leads
        $totalLeads = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();
        
        // Leads par statut
        $leadsByStatus = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();
        
        // Leads par source
        $leadsBySource = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select('source', DB::raw('count(*) as count'))
        ->groupBy('source')
        ->get();
        
        // Taux de conversion
        $convertedLeads = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'converted')
        ->whereBetween('converted_at', [$startDate, $endDate])
        ->count();
        
        $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;
        
        // Temps moyen de conversion
        $avgConversionTime = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->where('status', 'converted')
        ->whereBetween('converted_at', [$startDate, $endDate])
        ->whereNotNull('created_at')
        ->get()
        ->avg(function ($lead) {
            return $lead->created_at->diffInDays($lead->converted_at);
        });
        
        // Leads par agent
        $leadsByAgent = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with('agent.user')
        ->get()
        ->groupBy('agent_id')
        ->map(function ($leads, $agentId) {
            $agent = $leads->first()->agent;
            $totalLeads = $leads->count();
            $convertedLeads = $leads->where('status', 'converted')->count();
            $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;
            
            return [
                'agent' => $agent,
                'totalLeads' => $totalLeads,
                'convertedLeads' => $convertedLeads,
                'conversionRate' => $conversionRate,
            ];
        })
        ->sortByDesc(function ($item) {
            return $item['totalLeads'];
        })
        ->values()
        ->all();
        
        // Leads par jour
        $leadsPerDay = Lead::whereHas('agent', function ($query) use ($agencyId) {
            $query->where('agency_id', $agencyId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        // Formater les données pour les graphiques
        $leadsChartData = [];
        
        // Créer un tableau de tous les jours dans la période
        $period = Carbon::parse($startDate)->daysUntil($endDate);
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            
            $dayLead = $leadsPerDay->first(function ($item) use ($formattedDate) {
                return $item->date == $formattedDate;
            });
            
            $leadsChartData[] = [
                'date' => $date->format('d/m/Y'),
                'count' => $dayLead ? $dayLead->count : 0
            ];
        }
        
        return [
            'totalLeads' => $totalLeads,
            'leadsByStatus' => $leadsByStatus,
            'leadsBySource' => $leadsBySource,
            'convertedLeads' => $convertedLeads,
            'conversionRate' => $conversionRate,
            'avgConversionTime' => $avgConversionTime,
            'leadsByAgent' => $leadsByAgent,
            'leadsChartData' => $leadsChartData,
        ];
    }
    
    private function getMarketingStats($agencyId, $startDate, $endDate)
    {
        // Récupérer toutes les campagnes marketing de la période
        $campaigns = MarketingCampaign::where('agency_id', $agencyId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<', $startDate)
                            ->where(function ($r) use ($endDate) {
                                $r->where('end_date', '>=', $endDate)
                                    ->orWhereNull('end_date');
                            });
                    });
            })
            ->with('results')
            ->get();
        
        // Statistiques générales
        $totalCampaigns = $campaigns->count();
        $activeCampaigns = $campaigns->where('status', 'active')->count();
        $completedCampaigns = $campaigns->where('status', 'completed')->count();
        
        $totalBudget = $campaigns->sum('budget');
        $totalCost = $campaigns->sum('cost');
        
        // Statistiques par type de campagne
        $campaignsByType = $campaigns->groupBy('type')
            ->map(function ($items, $type) {
                return [
                    'type' => $type,
                    'count' => $items->count(),
                    'budget' => $items->sum('budget'),
                    'cost' => $items->sum('cost'),
                ];
            })
            ->values()
            ->all();
        
        // Résultats des campagnes
        $totalImpressions = 0;
        $totalClicks = 0;
        $totalLeadsGenerated = 0;
        $totalConversions = 0;
        $totalRevenue = 0;
        
        foreach ($campaigns as $campaign) {
            $totalImpressions += $campaign->results->sum('impressions');
            $totalClicks += $campaign->results->sum('clicks');
            $totalLeadsGenerated += $campaign->results->sum('leads_generated');
            $totalConversions += $campaign->results->sum('conversions');
            $totalRevenue += $campaign->results->sum('revenue');
        }
        
        // Calcul des KPIs
        $clickThroughRate = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
        $conversionRate = $totalLeadsGenerated > 0 ? ($totalConversions / $totalLeadsGenerated) * 100 : 0;
        $costPerLead = $totalLeadsGenerated > 0 ? $totalCost / $totalLeadsGenerated : 0;
        $roi = $totalCost > 0 ? (($totalRevenue - $totalCost) / $totalCost) * 100 : 0;
        
        // Résultats par jour
        $resultsPerDay = [];
        $period = Carbon::parse($startDate)->daysUntil($endDate);
        
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $dayResults = [
                'date' => $date->format('d/m/Y'),
                'impressions' => 0,
                'clicks' => 0,
                'leads' => 0,
                'conversions' => 0,
                'cost' => 0,
                'revenue' => 0,
            ];
            
            foreach ($campaigns as $campaign) {
                $result = $campaign->results->first(function ($item) use ($formattedDate) {
                    return $item->date->format('Y-m-d') == $formattedDate;
                });
                
                if ($result) {
                    $dayResults['impressions'] += $result->impressions;
                    $dayResults['clicks'] += $result->clicks;
                    $dayResults['leads'] += $result->leads_generated;
                    $dayResults['conversions'] += $result->conversions;
                    $dayResults['cost'] += $result->cost;
                    $dayResults['revenue'] += $result->revenue;
                }
            }
            
            $resultsPerDay[] = $dayResults;
        }
        
        // Campagnes les plus performantes
        $topCampaigns = $campaigns->map(function ($campaign) {
            $totalLeads = $campaign->results->sum('leads_generated');
            $totalConversions = $campaign->results->sum('conversions');
            $totalCost = $campaign->results->sum('cost');
            $totalRevenue = $campaign->results->sum('revenue');
            
            $roi = $totalCost > 0 ? (($totalRevenue - $totalCost) / $totalCost) * 100 : 0;
            
            return [
                'campaign' => $campaign,
                'leads' => $totalLeads,
                'conversions' => $totalConversions,
                'cost' => $totalCost,
                'revenue' => $totalRevenue,
                'roi' => $roi,
            ];
        })
        ->sortByDesc('roi')
        ->values()
        ->take(5)
        ->all();
        
        return [
            'totalCampaigns' => $totalCampaigns,
            'activeCampaigns' => $activeCampaigns,
            'completedCampaigns' => $completedCampaigns,
            'totalBudget' => $totalBudget,
            'totalCost' => $totalCost,
            'campaignsByType' => $campaignsByType,
            'totalImpressions' => $totalImpressions,
            'totalClicks' => $totalClicks,
            'totalLeadsGenerated' => $totalLeadsGenerated,
            'totalConversions' => $totalConversions,
            'totalRevenue' => $totalRevenue,
            'clickThroughRate' => $clickThroughRate,
            'conversionRate' => $conversionRate,
            'costPerLead' => $costPerLead,
            'roi' => $roi,
            'resultsPerDay' => $resultsPerDay,
            'topCampaigns' => $topCampaigns,
        ];
    }

    
    
    private function generatePDF($agency, $reportType, $startDate, $endDate, $stats)
    {
        // Logique pour générer un PDF
        // Utiliser une bibliothèque comme dompdf, mpdf, etc.
        
        return redirect()->back()->with('success', 'Rapport PDF généré avec succès.');
    }
    
    private function generateCSV($agency, $reportType, $startDate, $endDate, $stats)
    {
        // Logique pour générer un CSV
        
        return redirect()->back()->with('success', 'Rapport CSV généré avec succès.');
    }
    
    private function generateExcel($agency, $reportType, $startDate, $endDate, $stats)
    {
        // Logique pour générer un fichier Excel
        // Utiliser une bibliothèque comme PhpSpreadsheet
        
        return redirect()->back()->with('success', 'Rapport Excel généré avec succès.');
    }
}
