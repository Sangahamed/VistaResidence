<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Services Externes
    |--------------------------------------------------------------------------
    |
    | Configuration des services externes pour la synchronisation des propriétés.
    |
    */
    
    'seloger' => [
    'enabled' => env('SELOGER_ENABLED', false),
    'api_key' => env('SELOGER_API_KEY'),
    'api_secret' => env('SELOGER_API_SECRET'),
    'headers' => [
        'Authorization' => 'Bearer ' . env('SELOGER_API_KEY'),
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ],

        'endpoints' => [
            'create' => 'https://api.seloger.com/properties',
            'update' => 'https://api.seloger.com/properties/{id}',
            'delete' => 'https://api.seloger.com/properties/{id}',
        ],
        'field_mapping' => [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'address' => 'address',
            'city' => 'city',
            'postal_code' => 'zip_code',
            'country' => 'country',
            'property_type' => 'type',
            'transaction_type' => 'transaction_type',
            'bedrooms' => 'rooms.bedrooms',
            'bathrooms' => 'rooms.bathrooms',
            'area' => 'surface',
            'features' => 'amenities',
            'images' => 'photos',
        ],
    ],
    
    'leboncoin' => [
        'enabled' => env('LEBONCOIN_ENABLED', false),
        'api_key' => env('LEBONCOIN_API_KEY'),
        'api_secret' => env('LEBONCOIN_API_SECRET'),
        'headers' => [
            'Authorization' => 'Bearer ' . env('LEBONCOIN_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'endpoints' => [
            'create' => 'https://api.leboncoin.fr/api/v1/properties',
            'update' => 'https://api.leboncoin.fr/api/v1/properties/{id}',
            'delete' => 'https://api.leboncoin.fr/api/v1/properties/{id}',
        ],
        'field_mapping' => [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'address' => 'location.address',
            'city' => 'location.city',
            'postal_code' => 'location.zip_code',
            'country' => 'location.country',
            'property_type' => 'category',
            'transaction_type' => 'ad_type',
            'bedrooms' => 'attributes.rooms',
            'bathrooms' => 'attributes.bathrooms',
            'area' => 'attributes.square_meters',
            'features' => 'attributes.features',
            'images' => 'images',
        ],
    ],
    
    'pap' => [
        'enabled' => env('PAP_ENABLED', false),
        'api_key' => env('PAP_API_KEY'),
        'headers' => [
            'X-API-KEY' => env('PAP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'endpoints' => [
            'create' => 'https://api.pap.fr/v1/properties',
            'update' => 'https://api.pap.fr/v1/properties/{id}',
            'delete' => 'https://api.pap.fr/v1/properties/{id}',
        ],
        'field_mapping' => [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'address' => 'address',
            'city' => 'city',
            'postal_code' => 'postal_code',
            'country' => 'country',
            'property_type' => 'type',
            'transaction_type' => 'transaction_type',
            'bedrooms' => 'bedrooms',
            'bathrooms' => 'bathrooms',
            'area' => 'area',
            'features' => 'features',
            'images' => 'images',
        ],
    ],
];