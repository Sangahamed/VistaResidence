<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(string $action, ?Model $model = null, ?string $description = null, ?array $properties = null)
    {
        $user = Auth::user();
        
        $log = new ActivityLog([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $description ?? $action,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
        
        if ($model) {
            $log->model_type = get_class($model);
            $log->model_id = $model->getKey();
        }
        
        $log->save();
        
        return $log;
    }
    
    // Méthodes spécifiques pour les actions courantes
    public static function logPropertyView(Model $property)
    {
        return self::log('property_viewed', $property, 'Propriété consultée: ' . $property->title);
    }
    
    public static function logLeadCreated(Model $lead)
    {
        return self::log('lead_created', $lead, 'Lead créé: ' . $lead->name);
    }
    
    public static function logPropertyCreated(Model $property)
    {
        return self::log('property_created', $property, 'Propriété créée: ' . $property->title);
    }
    
    public static function logVisitRequested(Model $visit)
    {
        return self::log('visit_requested', $visit, 'Visite demandée pour: ' . $visit->property->title);
    }
}