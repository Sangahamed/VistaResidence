<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportGenerator
{
    public function generate(Carbon $startDate, Carbon $endDate, string $type): array
    {
        $data = [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'type' => $type
            ],
            'summary' => $this->generateSummary($startDate, $endDate),
            'user_activity' => $this->generateUserActivity($startDate, $endDate),
            'suspicious_activity' => $this->generateSuspiciousActivity($startDate, $endDate),
            'geo_activity' => $this->generateGeoActivity($startDate, $endDate),
            'financial_metrics' => $this->generateFinancialMetrics($startDate, $endDate),
            'pdf' => $this->generatePdf($startDate, $endDate, $type)
        ];

        return $data;
    }

    protected function generateSummary(Carbon $startDate, Carbon $endDate): array
    {
        $totalActivities = ActivityLog::whereBetween('created_at', [$startDate, $endDate])->count();
        $suspiciousActivities = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_suspicious', true)
            ->count();
        
        $uniqueUsers = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
        
        $uniqueIps = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('ip_address')
            ->count('ip_address');
        
        $topActions = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->select('action', DB::raw('count(*) as total'))
            ->groupBy('action')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        return [
            'total_activities' => $totalActivities,
            'suspicious_activities' => $suspiciousActivities,
            'suspicious_percentage' => $totalActivities > 0 ? round(($suspiciousActivities / $totalActivities) * 100, 2) : 0,
            'unique_users' => $uniqueUsers,
            'unique_ips' => $uniqueIps,
            'top_actions' => $topActions
        ];
    }

    protected function generateUserActivity(Carbon $startDate, Carbon $endDate): array
    {
        $mostActiveUsers = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('user:id,name,email')
            ->get();
        
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        $usersByDay = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(DISTINCT user_id) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'most_active_users' => $mostActiveUsers,
            'new_users' => $newUsers,
            'users_by_day' => $usersByDay
        ];
    }

    protected function generateSuspiciousActivity(Carbon $startDate, Carbon $endDate): array
    {
        $suspiciousTypes = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_suspicious', true)
            ->select('suspicion_type', DB::raw('count(*) as total'))
            ->groupBy('suspicion_type')
            ->orderByDesc('total')
            ->get();
        
        $highRiskActivities = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_suspicious', true)
            ->where('risk_score', '>=', 75)
            ->with('user:id,name,email')
            ->orderByDesc('risk_score')
            ->limit(10)
            ->get();
        
        return [
            'suspicious_types' => $suspiciousTypes,
            'high_risk_activities' => $highRiskActivities
        ];
    }

    protected function generateGeoActivity(Carbon $startDate, Carbon $endDate): array
    {
        $countries = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('metadata->geo->country')
            ->select(
                DB::raw("JSON_EXTRACT(metadata, '$.geo.country') as country"),
                DB::raw('count(*) as total')
            )
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        $vpnUsage = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('metadata->is_vpn')
            ->select(
                DB::raw("CASE WHEN JSON_EXTRACT(metadata, '$.is_vpn') = 'true' THEN 'VPN/Proxy' ELSE 'Direct' END as connection_type"),
                DB::raw('count(*) as total')
            )
            ->groupBy('connection_type')
            ->get();
        
        return [
            'countries' => $countries,
            'vpn_usage' => $vpnUsage
        ];
    }

    protected function generateFinancialMetrics(Carbon $startDate, Carbon $endDate): array
    {
        // Cette méthode devrait être adaptée selon votre modèle de données financières
        // Exemple simplifié avec des données fictives
        
        $paymentActivities = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'payment')
            ->count();
        
        // Simuler des données financières (à remplacer par vos propres requêtes)
        $totalRevenue = rand(5000, 20000);
        $averageOrderValue = rand(50, 200);
        
        return [
            'payment_activities' => $paymentActivities,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
            'conversion_rate' => rand(1, 5)
        ];
    }

    protected function generatePdf(Carbon $startDate, Carbon $endDate, string $type): string
    {
        $data = [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'type' => $type
            ],
            'summary' => $this->generateSummary($startDate, $endDate),
            'user_activity' => $this->generateUserActivity($startDate, $endDate),
            'suspicious_activity' => $this->generateSuspiciousActivity($startDate, $endDate),
            'geo_activity' => $this->generateGeoActivity($startDate, $endDate),
            'financial_metrics' => $this->generateFinancialMetrics($startDate, $endDate)
        ];
        
        $pdf = PDF::loadView('reports.activity', $data);
        
        $filename = 'activity_report_' . $type . '_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.pdf';
        $path = storage_path('app/reports/' . $filename);
        
        // Assurez-vous que le répertoire existe
        if (!file_exists(storage_path('app/reports'))) {
            mkdir(storage_path('app/reports'), 0755, true);
        }
        
        $pdf->save($path);
        
        return $path;
    }
}
