<?php

return [
    'suspicious_frequency' => env('ACTIVITY_SUSPICIOUS_FREQ', 30),
    
    'deep_analysis_actions' => [
        'login', 'password_reset', 'delete', 
        'admin_access', 'payment'
    ],
    
    'ai' => [
        'enabled' => env('ACTIVITY_AI_ENABLED', true),
        'model' => env('ACTIVITY_AI_MODEL', 'gpt-4'),
        'min_risk_score' => env('ACTIVITY_AI_MIN_RISK', 50),
    ],
    
    'notifications' => [
        'enabled' => env('ACTIVITY_NOTIFICATIONS_ENABLED', true),
        'channels' => ['mail', 'database'],
        'throttle' => env('ACTIVITY_NOTIFICATIONS_THROTTLE', 5), // minutes
    ],
    
    'reports' => [
        'daily' => [
            'enabled' => env('ACTIVITY_REPORT_DAILY_ENABLED', true),
            'time' => env('ACTIVITY_REPORT_DAILY_TIME', '23:00'),
            'recipients' => explode(',', env('ACTIVITY_REPORT_RECIPIENTS', 'admin@example.com')),
        ],
        'monthly' => [
            'enabled' => env('ACTIVITY_REPORT_MONTHLY_ENABLED', true),
            'day' => env('ACTIVITY_REPORT_MONTHLY_DAY', 1), // 1er du mois
            'time' => env('ACTIVITY_REPORT_MONTHLY_TIME', '01:00'),
            'recipients' => explode(',', env('ACTIVITY_REPORT_RECIPIENTS', 'admin@example.com')),
        ],
        'quarterly' => [
            'enabled' => env('ACTIVITY_REPORT_QUARTERLY_ENABLED', true),
            'months' => [1, 4, 7, 10], // Janvier, Avril, Juillet, Octobre
            'day' => env('ACTIVITY_REPORT_QUARTERLY_DAY', 1),
            'time' => env('ACTIVITY_REPORT_QUARTERLY_TIME', '02:00'),
            'recipients' => explode(',', env('ACTIVITY_REPORT_RECIPIENTS', 'admin@example.com')),
        ],
    ],
];
