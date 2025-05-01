<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Configuration des Leads
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient la configuration pour la gestion des leads.
    |
    */
    
    // Seuil d'inactivité pour les leads (en jours)
    'warning_threshold' => env('LEAD_WARNING_THRESHOLD', 7),
    'critical_threshold' => env('LEAD_CRITICAL_THRESHOLD', 14),
    
    // Statuts des leads
    'statuses' => [
        'new' => 'Nouveau',
        'contacted' => 'Contacté',
        'qualified' => 'Qualifié',
        'negotiation' => 'En négociation',
        'converted' => 'Converti',
        'lost' => 'Perdu',
        'closed' => 'Fermé',
    ],
    
    // Sources des leads
    'sources' => [
        'website' => 'Site web',
        'phone' => 'Téléphone',
        'email' => 'Email',
        'referral' => 'Parrainage',
        'social_media' => 'Réseaux sociaux',
        'portal' => 'Portail immobilier',
        'event' => 'Événement',
        'other' => 'Autre',
    ],
    
    // Types d'activités des leads
    'activity_types' => [
        'note' => 'Note',
        'call' => 'Appel',
        'email' => 'Email',
        'meeting' => 'Rendez-vous',
        'visit' => 'Visite',
        'offer' => 'Offre',
        'system' => 'Système',
    ],
];