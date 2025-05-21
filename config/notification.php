<?php

return [
    'default_preferences' => [
        'email_enabled' => true,
        'push_enabled' => true,
        'sms_enabled' => false,
        'frequency' => 'instant',
        'alerts' => [
            'properties' => [
                'new' => true,
                'price_change' => true,
                'status_change' => true
            ],
            'visits' => [
                'requested' => true,
                'confirmed' => true,
                'cancelled' => true
            ]
        ]
    ],

    // ✅ Ajoutez cette partie :
    'types' => [
        'properties' => [
            'new' => 'Nouveau bien',
            'price_change' => 'Changement de prix',
            'status_change' => 'Changement de statut'
        ],
        'visits' => [
            'requested' => 'Visite demandée',
            'confirmed' => 'Visite confirmée',
            'cancelled' => 'Visite annulée'
        ],
        'favorites' => [
            'added' => 'Ajouté aux favoris',
            'removed' => 'Retiré des favoris'
        ],
        'searches' => [
            'match_found' => 'Nouveau bien correspondant à votre recherche'
        ]
    ]
];
