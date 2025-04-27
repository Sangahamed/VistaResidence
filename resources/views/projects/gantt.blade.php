@extends('layouts.app')

@section('title', 'Diagramme de Gantt')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Diagramme de Gantt - {{ $project->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Visualisez la planification du projet.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.show', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au projet
                </a>
                <a href="{{ route('tasks.create', ['company' => $company->id, 'project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle tâche
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="p-4">
                <div class="mb-4 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Période du projet: 
                        <span class="font-medium">{{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}</span> 
                        au 
                        <span class="font-medium">{{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="gantt-view-mode px-3 py-1 text-sm font-medium rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 active" data-mode="Day">Jour</button>
                        <button class="gantt-view-mode px-3 py-1 text-sm font-medium rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50" data-mode="Week">Semaine</button>
                        <button class="gantt-view-mode px-3 py-1 text-sm font-medium rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50" data-mode="Month">Mois</button>
                    </div>
                </div>
                <div id="gantt-chart" data-company-id="{{ $company->id }}" data-project-id="{{ $project->id }}" data-tasks="{{ json_encode($ganttData) }}" class="gantt-container" style="height: 500px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.css">
    <script src="{{ asset('js/gantt-chart.js') }}"></script>
    <style>
        .gantt .bar-wrapper:hover .bar {
            fill: #4f46e5 !important;
        }
        .gantt .bar-label {
            font-size: 12px;
        }
        .gantt .handle {
            fill: #4f46e5;
        }
        .gantt .lower-text, .gantt .upper-text {
            font-size: 10px;
        }
        .gantt-popup {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            padding: 10px;
            max-width: 300px;
        }
        .gantt-popup h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
        }
        .gantt-popup p {
            margin: 0;
            font-size: 12px;
            line-height: 1.5;
        }
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 4px;
            color: white;
            z-index: 1000;
            animation: fadeIn 0.3s ease-in-out;
        }
        .notification.success {
            background-color: #10b981;
        }
        .notification.error {
            background-color: #ef4444;
        }
        .notification.fade-out {
            animation: fadeOut 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(10px); }
        }
    </style>
@endpush
