<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Configuration des Propriétés
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient la configuration pour la gestion des propriétés.
    |
    */
    
    // Durée d'expiration des propriétés publiées (en jours)
    'expiration_days' => env('PROPERTY_EXPIRATION_DAYS', 90),
    
    // Statuts des propriétés
    'statuses' => [
        'draft' => 'Brouillon',
        'pending' => 'En attente',
        'published' => 'Publiée',
        'sold' => 'Vendue',
        'rented' => 'Louée',
        'expired' => 'Expirée',
        'archived' => 'Archivée',
    ],
    
    // Types de transactions
    'transaction_types' => [
        'sale' => 'Vente',
        'rent' => 'Location',
    ],
    
    // Limites de téléchargement d'images
    'max_images' => env('PROPERTY_MAX_IMAGES', 20),
    'max_image_size' => env('PROPERTY_MAX_IMAGE_SIZE', 5120), // en KB (5 MB)
    
    // Formats d'images autorisés
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp'],
    
    // Dimensions des images
    'image_dimensions' => [
        'thumbnail' => [
            'width' => 300,
            'height' => 200,
        ],
        'medium' => [
            'width' => 800,
            'height' => 600,
        ],
        'large' => [
            'width' => 1200,
            'height' => 800,
        ],
    ],
];