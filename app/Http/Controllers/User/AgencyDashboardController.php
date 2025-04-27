<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\Property;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgencyDashboardController extends Controller
{
    public function index()
    {
        // Get the current user's agency
        $user = Auth::user();
        $agency = null;
        
        if ($user->role === 'admin') {
            // Admin can see all agencies, default to the first one
            $agency = Agency::first();
        } elseif ($user->role === 'agency_admin') {
            // Agency admin sees their own agency
            $agent = Agent::where('user_id', $user->id)->first();
            $agency = $agent ? $agent->agency : null;
        }
        
        if (!$agency) {
            return redirect()->route('home')->with('error', 'Aucune agence trouvée.');
        }
        
        // Get all agencies for the dropdown selector
        $agencies = Agency::all();
        
        // Get agency statistics
        $totalAgents = $agency->agents()->count();
        $activeAgents = $agency->agents()->where('is_active', true)->count();
        
        $totalProperties = $agency->properties()->count();
        $activeListings = $agency->properties()->where('status', 'active')->count();
        $soldProperties = $agency->properties()->where('status', 'sold')->count();
        
        // Get leads statistics
        $totalLeads = Lead::whereHas('agent', function ($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->count();
        
        $newLeads = Lead::whereHas('agent', function ($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->where('status', 'new')->count();
        
        $convertedLeads = Lead::whereHas('agent', function ($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })->where('status', 'converted')->count();
        
        // Get monthly sales data for the chart
        $monthlySales = Property::whereHas('agent', function ($query) use ($agency) {
            $query->where('agency_id', $agency->id);
        })
        ->where('status', 'sold')
        ->where('sold_at', '>=', Carbon::now()->subMonths(12))
        ->selectRaw('MONTH(sold_at) as month, YEAR(sold_at) as year, COUNT(*) as count, SUM(price) as total')
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        
        // Format data for the chart
        $salesChartData = [];
        $revenueChartData = [];
        
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $month = $date->month;
            $year = $date->year;
            
            $monthlySale = $monthlySales->first(function ($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });   
               
            
            $salesChartData[] = [
                'month' => $date->format('M'),
                'count' => $monthlySale ? $monthlySale->count : 0
            ];
            
            $revenueChartData[] = [
                'month' => $date->format('M'),
                'total' => $monthlySale ? $monthlySale->total : 0
            ];
        }
        
        // Get top performing agents
        $topAgents = Agent::where('agency_id', $agency->id)
            ->withCount(['properties as sold_count' => function ($query) {
                $query->where('status', 'sold');
            }])
            ->orderBy('sold_count', 'desc')
            ->take(5)
            ->get();
        
        return view('agency.dashboard', compact(
            'agency',
            'agencies',
            'totalAgents',
            'activeAgents',
            'totalProperties',
            'activeListings',
            'soldProperties',
            'totalLeads',
            'newLeads',
            'convertedLeads',
            'salesChartData',
            'revenueChartData',
            'topAgents'
        ));
    }
    
    public function switchAgency(Request $request)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id'
        ]);
        
        $agency = Agency::findOrFail($request->agency_id);
        
        return redirect()->route('agency.dashboard', ['agency' => $agency->id]);
    }
}
