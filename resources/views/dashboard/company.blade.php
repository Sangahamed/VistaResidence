<!-- resources/views/dashboard/company.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Tableau de bord entreprise</h1>
                <p class="text-muted">Bienvenue sur votre espace professionnel. Gérez vos équipes, projets et biens
                    immobiliers.</p>
            </div>
        </div>

        <!-- Ajout des liens pour les fonctionnalités entreprises -->
        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'agency_admin')
            <div class="py-2">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Gestion d'entreprise
                </h3>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('agencies.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('agencies.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('agencies.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Agences
                    </a>

                    <a href="{{ route('agents.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('agents.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('agents.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Agents
                    </a>

                    <a href="{{ route('agency.dashboard') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('agency.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('agency.dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Tableau de bord
                    </a>

                    <a href="{{ route('leads.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('leads.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('leads.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                        Leads & Prospects
                    </a>

                    <a href="{{ route('marketing.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('marketing.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('marketing.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        Marketing
                    </a>

                    <a href="{{ route('reports.index') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('reports.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Rapports
                    </a>
                </div>
            </div>
        @endif
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Propriétés</h6>
                                <h3>{{ $stats['properties_count'] }}</h3>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-building text-primary"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">+5 depuis le mois dernier</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Membres d'équipe</h6>
                                <h3>{{ $stats['team_members'] }}</h3>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-users text-info"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">+1 depuis le mois dernier</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Projets actifs</h6>
                                <h3>{{ $stats['active_projects'] }}</h3>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-project-diagram text-success"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">+3 depuis le mois dernier</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Tâches complétées</h6>
                                <h3>{{ $stats['completed_tasks'] }}</h3>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <i class="fas fa-check-circle text-warning"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">+15 depuis le mois dernier</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Projets récents -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Projets récents</h5>
                    </div>
                    <div class="card-body">
                        @if (count($recentProjects) > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($recentProjects as $project)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light p-2 rounded me-3">
                                                <i class="fas fa-project-diagram text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $project->name }}</h6>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted me-2">Progression:
                                                        {{ $project->progress }}%</span>
                                                    <div class="progress flex-grow-1" style="height: 5px;">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $project->progress }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">Échéance:
                                                    {{ $project->deadline->format('d/m/Y') }}</small>
                                                <a href="#" class="btn btn-sm btn-outline-primary mt-1">Détails</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">Aucun projet récent</p>
                                <button class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Créer un projet
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Performance des équipes -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Performance des équipes</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="teamPerformanceChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Propriétés gérées -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Propriétés gérées</h3>
                    <a href="{{ route('properties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une propriété
                    </a>
                </div>

                <div class="row">
                    @if (count($properties) > 0)
                        @foreach ($properties as $property)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="{{ $property->featured_image ?? 'https://via.placeholder.com/300x200' }}"
                                        class="card-img-top" alt="{{ $property->title }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">{{ $property->title }}</h5>
                                            <span
                                                class="badge {{ $property->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $property->status === 'active' ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <p class="card-text text-muted">{{ $property->location }}</p>
                                        <p class="card-text fw-bold">{{ number_format($property->price) }} €</p>
                                        <p class="card-text text-muted">
                                            {{ $property->surface }} m² - {{ $property->bedrooms }} ch.
                                        </p>
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="{{ route('properties.show', $property) }}"
                                                class="btn btn-sm btn-outline-primary">Détails</a>
                                            <a href="{{ route('properties.edit', $property) }}"
                                                class="btn btn-sm btn-outline-secondary">Modifier</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info">
                                Aucune propriété n'est gérée par votre entreprise.
                                <a href="{{ route('properties.create') }}" class="alert-link">Ajouter une propriété</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Membres de l'équipe -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Membres de l'équipe</h5>
                        <a href="{{ route('invitations.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i> Inviter un membre
                        </a>
                    </div>
                    <div class="card-body">
                        @if (count($teamMembers) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teamMembers as $member)
                                            <tr>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->email }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $member->role_name }}</span>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">Profil</a>
                                                    <a href="#" class="btn btn-sm btn-outline-secondary">Modifier le
                                                        rôle</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">Aucun membre dans l'équipe</p>
                                <a href="{{ route('invitations.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Inviter un membre
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Statistiques -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-rose-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Entreprises </dt>
                                <p>nombre de filiale</p>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">{{ $stats['companies'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('companies.index') }}"
                            class="font-medium text-rose-700 hover:text-rose-900">Voir toutes</a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Utilisateurs</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">{{ $stats['users'] ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="" class="font-medium text-emerald-700 hover:text-emerald-900">Voir tous</a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Projets</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900">{{ $stats['projects'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        @if (isset($company))
                            <a href="{{ route('projects.index', ['company' => $company->id]) }}"
                                class="font-medium text-amber-700 hover:text-amber-900">Voir tous</a>
                        @endif


                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-sky-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Tâches</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">{{ $stats['tasks'] }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">

                            {{-- <a href="{{ route('tasks.index') }}" class="font-medium text-sky-700 hover:text-sky-900">Voir
                                toutes</a> --}}

                            @if (isset($recentProjects[0]))
                                <a href="{{ route('tasks.index', ['company' => $recentProjects[0]->company_id, 'project' => $recentProjects[0]->id]) }}"
                                    class="text-sm font-medium text-rose-600 hover:text-rose-500">
                                    Voir toutes
                                </a>
                            @else
                                <span class="text-sm text-gray-400">Aucune tâche</span>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
                <!-- Tâches récentes -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Tâches récentes</h3>
                        @if(isset($recentProjects[0]))
                        <a href="{{ route('tasks.index', ['company' => $recentProjects[0]->company_id, 'project' => $recentProjects[0]->id]) }}"
                            class="text-sm font-medium text-rose-600 hover:text-rose-500">
                            Voir toutes
                        </a>
                        @else
                    <span class="text-muted">Aucune tâche</span>
                @endif
                    </div>
                    <div class="border-t border-gray-200 divide-y divide-gray-200">
                        @forelse($recentTasks as $task)
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span
                                                class="h-8 w-8 rounded-full flex items-center justify-center 
                                            {{ $task->status === 'to_do'
                                                ? 'bg-gray-100'
                                                : ($task->status === 'in_progress'
                                                    ? 'bg-blue-100'
                                                    : ($task->status === 'review'
                                                        ? 'bg-purple-100'
                                                        : 'bg-green-100')) }}">
                                                <svg class="h-5 w-5 
                                                {{ $task->status === 'to_do'
                                                    ? 'text-gray-500'
                                                    : ($task->status === 'in_progress'
                                                        ? 'text-blue-500'
                                                        : ($task->status === 'review'
                                                            ? 'text-purple-500'
                                                            : 'text-green-500')) }}"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    @if ($task->status === 'completed')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('tasks.show', ['company' => $task->project->company_id, 'project' => $task->project_id, 'task' => $task->id]) }}"
                                                class="text-sm font-medium text-gray-900 hover:text-gray-600">
                                                {{ $task->name }}
                                            </a>
                                            <div class="text-xs text-gray-500">
                                                {{ $task->project->name }} - {{ $task->project->company->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $task->priority === 'low'
                                            ? 'bg-green-100 text-green-800'
                                            : ($task->priority === 'medium'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            @if ($task->due_date)
                                                <span
                                                    class="text-xs {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-500' }}">
                                                    {{ $task->due_date->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-5 text-center text-sm text-gray-500">
                                Aucune tâche récente
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Projets récents -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Projets récents</h3>
                        @if(isset($recentProjects[0]))
                        <a href="{{ route('projects.index', ['company' => $recentProjects[0]->company_id]) }}"
                            class="text-sm font-medium text-rose-600 hover:text-rose-500">
                            Voir tous
                        </a>
                        @endif
                    </div>
                    <div class="border-t border-gray-200 divide-y divide-gray-200">
                        @forelse($recentProjects as $project)
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span
                                                class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('projects.show', ['company' => $project->company_id, 'project' => $project->id]) }}"
                                                class="text-sm font-medium text-gray-900 hover:text-gray-600">
                                                {{ $project->name }}
                                            </a>
                                            <div class="text-xs text-gray-500">
                                                {{ $project->company->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $project->status === 'not_started'
                                            ? 'bg-gray-100 text-gray-800'
                                            : ($project->status === 'in_progress'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($project->status === 'on_hold'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-green-100 text-green-800')) }}">
                                            {{ $project->status === 'not_started'
                                                ? 'Non démarré'
                                                : ($project->status === 'in_progress'
                                                    ? 'En cours'
                                                    : ($project->status === 'on_hold'
                                                        ? 'En pause'
                                                        : 'Terminé')) }}
                                        </span>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <div class="flex -space-x-1 overflow-hidden">
                                                @foreach ($project->users->take(3) as $user)
                                                    <img class="h-6 w-6 rounded-full ring-2 ring-white"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF"
                                                        alt="{{ $user->name }}">
                                                @endforeach
                                                @if ($project->users->count() > 3)
                                                    <span
                                                        class="flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-xs font-medium text-gray-500 ring-2 ring-white">
                                                        +{{ $project->users->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-5 text-center text-sm text-gray-500">
                                Aucun projet récent
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Activité récente -->
                <div class="lg:col-span-2 bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Activité récente</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @forelse($activities as $activity)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full flex items-center justify-center 
                                                    {{ $activity->type === 'task_created'
                                                        ? 'bg-sky-100'
                                                        : ($activity->type === 'task_completed'
                                                            ? 'bg-green-100'
                                                            : ($activity->type === 'project_created'
                                                                ? 'bg-amber-100'
                                                                : ($activity->type === 'project_completed'
                                                                    ? 'bg-emerald-100'
                                                                    : 'bg-gray-100'))) }}">
                                                        <svg class="h-5 w-5 
                                                        {{ $activity->type === 'task_created'
                                                            ? 'text-sky-600'
                                                            : ($activity->type === 'task_completed'
                                                                ? 'text-green-600'
                                                                : ($activity->type === 'project_created'
                                                                    ? 'text-amber-600'
                                                                    : ($activity->type === 'project_completed'
                                                                        ? 'text-emerald-600'
                                                                        : 'text-gray-600'))) }}"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            @if (strpos($activity->type, 'task') !== false)
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                            @elseif(strpos($activity->type, 'project') !== false)
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                            @endif
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            {!! $activity->description !!}
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time
                                                            datetime="{{ $activity->created_at->format('Y-m-d H:i:s') }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-4 py-5 text-center text-sm text-gray-500">
                                        Aucune activité récente
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Calendrier -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Calendrier</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">{{ $currentMonth }}</h2>
                                <div class="flex space-x-2">
                                    <a href="{{ route('dashboard', ['month' => $prevMonth]) }}"
                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('dashboard', ['month' => $nextMonth]) }}"
                                        class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="grid grid-cols-7 gap-1 text-center text-xs leading-6 text-gray-500 mb-1">
                                <div>Lun</div>
                                <div>Mar</div>
                                <div>Mer</div>
                                <div>Jeu</div>
                                <div>Ven</div>
                                <div>Sam</div>
                                <div>Dim</div>
                            </div>
                            <div class="grid grid-cols-7 gap-1 text-sm">
                                @foreach ($calendar as $day)
                                    <div class="aspect-w-1 aspect-h-1">
                                        <button type="button"
                                            class="w-full h-full flex flex-col p-1 border rounded-lg 
                                        {{ $day['isCurrentMonth'] ? ($day['isToday'] ? 'bg-rose-50 border-rose-200' : 'bg-white border-gray-200') : 'bg-gray-50 border-gray-100 text-gray-400' }}
                                        {{ $day['hasEvents'] ? 'ring-2 ring-offset-1 ring-rose-500' : '' }}">
                                            <time datetime="{{ $day['date'] }}"
                                                class="ml-auto {{ $day['isToday'] ? 'flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-white' : '' }}">
                                                {{ $day['dayNumber'] }}
                                            </time>
                                            @if ($day['hasEvents'])
                                                <span
                                                    class="mt-auto -mx-1 rounded-b-lg bg-rose-100 text-rose-700 text-xs px-1 py-0.5 text-center">
                                                    {{ $day['eventCount'] }}
                                                    {{ $day['eventCount'] > 1 ? 'tâches' : 'tâche' }}
                                                </span>
                                            @endif
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Données pour le graphique de performance des équipes (exemple)
                    const teamData = {
                        labels: ['Commerciale', 'Technique', 'Administrative', 'Juridique', 'Marketing'],
                        datasets: [{
                            label: 'Tâches complétées',
                            data: [15, 12, 8, 5, 7],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    };

                    const ctx = document.getElementById('teamPerformanceChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: teamData,
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>
        @endpush
    @endsection
