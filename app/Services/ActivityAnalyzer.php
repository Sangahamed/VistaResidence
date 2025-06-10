<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Services\GeoLocator;
use Illuminate\Support\Facades\Cache;
use OpenAI\Laravel\Facades\OpenAI;

class ActivityAnalyzer
{
    public function __construct(
        protected GeoLocator $geoLocator
    ) {}

    public function analyze(ActivityLog $activity): void
    {
        $this->enrichWithGeoData($activity);
        $this->checkFrequency($activity);
        
        if ($this->requiresDeepAnalysis($activity)) {
            $this->analyzeWithAI($activity);
        }
    }

    protected function enrichWithGeoData(ActivityLog $activity): void
    {
        $geoData = $this->geoLocator->locate($activity->ip_address);
        
        $activity->update([
            'metadata' => array_merge($activity->metadata ?? [], [
                'geo' => $geoData,
                'is_vpn' => $this->isVPN($geoData),
            ]),
        ]);
    }

    protected function isVPN($geoData): bool
    {
        // Logique simplifiée pour détecter les VPN
        return $geoData['is_proxy'] ?? false || $geoData['is_hosting'] ?? false;
    }

    protected function checkFrequency(ActivityLog $activity): void
    {
        $key = "activity_freq:{$activity->ip_address}";
        $count = Cache::remember($key, now()->addMinutes(5), function () use ($activity) {
            return ActivityLog::where('ip_address', $activity->ip_address)
                ->where('created_at', '>', now()->subMinutes(5))
                ->count();
        });

        if ($count > config('activity.suspicious_frequency', 30)) {
            $activity->update([
                'is_suspicious' => true,
                'suspicion_type' => 'high_frequency',
                'risk_score' => min(($count / 10) * 100, 100),
            ]);
        }
    }

    protected function requiresDeepAnalysis(ActivityLog $activity): bool
    {
        $deepAnalysisActions = config('activity.deep_analysis_actions', [
            'login', 'password_reset', 'delete', 'admin_access', 'payment'
        ]);
        
        return in_array($activity->action, $deepAnalysisActions)
            || ($activity->metadata['is_vpn'] ?? false);
    }

    protected function analyzeWithAI(ActivityLog $activity): void
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => config('activity.ai.model', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Vous êtes un analyste de sécurité spécialisé dans la détection d\'activités suspectes. Analysez cette activité et retournez un JSON avec: is_suspicious (booléen), suspicion_type (string|null), risk_score (0-100), recommendation (string), reason (string détaillé)'
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->formatActivityForAI($activity)
                    ]
                ],
                'temperature' => 0.7,
            ]);

            $content = $response->choices[0]->message->content;
            $analysis = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $activity->update([
                    'is_suspicious' => $analysis['is_suspicious'] ?? false,
                    'suspicion_type' => $analysis['suspicion_type'] ?? null,
                    'risk_score' => $analysis['risk_score'] ?? null,
                    'metadata' => array_merge($activity->metadata ?? [], [
                        'ai_analysis' => [
                            'recommendation' => $analysis['recommendation'] ?? null,
                            'reason' => $analysis['reason'] ?? null,
                            'analyzed_at' => now()->toDateTimeString(),
                        ]
                    ]),
                    'analyzed_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            logger()->error("AI Analysis failed for activity {$activity->id}: " . $e->getMessage());
        }
    }

    protected function formatActivityForAI(ActivityLog $activity): string
    {
        return "Analyse l'activité suivante et détermine si elle est suspecte.

Données :
- IP : {$activity->ip_address}
- User ID : {$activity->user_id}
- Action : {$activity->action}
- URL : {$activity->details}
- Agent : {$activity->user_agent}
- Géolocalisation : " . json_encode($activity->metadata['geo'] ?? []) . "
- Est VPN/Proxy : " . ($activity->metadata['is_vpn'] ? 'Oui' : 'Non') . "

Réponds en JSON : 
{
  \"is_suspicious\": true|false,
  \"suspicion_type\": \"brute_force|unusual_location|vpn_usage|rate_limiting|null\",
  \"risk_score\": 0-100,
  \"recommendation\": \"Action recommandée\",
  \"reason\": \"Explication détaillée\"
}";
    }
}
