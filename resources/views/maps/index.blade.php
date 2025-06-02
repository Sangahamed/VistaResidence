@extends('components.front.layouts.front')

@section('content')
    <style>
        /* Assurer que la carte prend toute la hauteur disponible */
        #map {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }

        /* Styles pour les marqueurs personnalisés */
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }

        .leaflet-marker-icon {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        /* Clusters */
        .marker-cluster-small {
            background-color: rgba(59, 130, 246, 0.6);
            transition: all 0.3s ease;
        }

        .marker-cluster-small div {
            background-color: rgba(59, 130, 246, 0.8);
        }

        .marker-cluster-small:hover {
            background-color: rgba(59, 130, 246, 0.8);
            transform: scale(1.1);
        }

        .marker-cluster-medium {
            background-color: rgba(249, 115, 22, 0.6);
            transition: all 0.3s ease;
        }

        .marker-cluster-medium div {
            background-color: rgba(249, 115, 22, 0.8);
        }

        .marker-cluster-medium:hover {
            background-color: rgba(249, 115, 22, 0.8);
            transform: scale(1.1);
        }

        .marker-cluster-large {
            background-color: rgba(239, 68, 68, 0.6);
            transition: all 0.3s ease;
        }

        .marker-cluster-large div {
            background-color: rgba(239, 68, 68, 0.8);
        }

        .marker-cluster-large:hover {
            background-color: rgba(239, 68, 68, 0.8);
            transform: scale(1.1);
        }

        /* User location marker */
        .user-location-marker {
            background: #3B82F6;
            border: 3px solid white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        /* Property cards */
        .property-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .leaflet-popup-content {
                width: 240px !important;
            }
        }

        /* Assurer que le contenu ne déborde pas */
        .min-h-0 {
            min-height: 0;
        }
    </style>
    <div class="h-screen">
        <livewire:properties-map />
    </div>
@endsection
