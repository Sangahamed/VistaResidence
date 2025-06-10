@extends('back.admin.layouts.app')

@section('title', 'Gestion des entreprises')

@section('content')
    @push('styles')
        /* Animations pour l'interface d'administration */

        /* Fade In */
        @keyframes fadeIn {
        from {
        opacity: 0;
        }
        to {
        opacity: 1;
        }
        }

        /* Fade In Down */
        @keyframes fadeInDown {
        from {
        opacity: 0;
        transform: translateY(-20px);
        }
        to {
        opacity: 1;
        transform: translateY(0);
        }
        }

        /* Fade In Up */
        @keyframes fadeInUp {
        from {
        opacity: 0;
        transform: translateY(20px);
        }
        to {
        opacity: 1;
        transform: translateY(0);
        }
        }

        /* Pulse */
        @keyframes pulse {
        0% {
        transform: scale(1);
        }
        50% {
        transform: scale(1.05);
        }
        100% {
        transform: scale(1);
        }
        }

        /* Shake */
        @keyframes shake {
        0%,
        100% {
        transform: translateX(0);
        }
        10%,
        30%,
        50%,
        70%,
        90% {
        transform: translateX(-5px);
        }
        20%,
        40%,
        60%,
        80% {
        transform: translateX(5px);
        }
        }

        /* Classes d'animation */
        .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-fade-in-down {
        animation: fadeInDown 0.5s ease-out forwards;
        }

        .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        }

        .animate-pulse {
        animation: pulse 2s infinite;
        }

        .animate-shake {
        animation: shake 0.5s;
        }

        /* Délais d'animation */
        .delay-100 {
        animation-delay: 0.1s;
        }
        .delay-200 {
        animation-delay: 0.2s;
        }
        .delay-300 {
        animation-delay: 0.3s;
        }
        .delay-400 {
        animation-delay: 0.4s;
        }
        .delay-500 {
        animation-delay: 0.5s;
        }

        /* Transitions */
        .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .duration-300 {
        transition-duration: 300ms;
        }

        .duration-500 {
        transition-duration: 500ms;
        }

        /* Hover effects */
        .hover-scale:hover {
        transform: scale(1.05);
        }

        .hover-lift:hover {
        transform: translateY(-5px);
        }

        /* Notification animations */
        .notification-enter {
        animation: fadeInDown 0.5s ease-out forwards;
        }

        .notification-exit {
        animation: fadeOut 0.5s ease-in forwards;
        }

        @keyframes fadeOut {
        from {
        opacity: 1;
        }
        to {
        opacity: 0;
        }
        }
    @endpush
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 transition-all duration-300 transform hover:scale-105">
            Gestion des entreprises
        </h1>

        <div class="flex space-x-2">
            <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}"
                class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Voir les demandes en attente
                </span>
            </a>
        </div>
    </div>

    <!-- Filtres avec animation -->
    <div class="bg-white rounded-lg shadow-md p-5 mb-6 transition-all duration-300 hover:shadow-lg">
        <form action="{{ route('admin.companies.index') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="transition-all duration-300 transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvées</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetées</option>
                </select>
            </div>

            <div class="transition-all duration-300 transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <select name="date"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="all" {{ request('date') == 'all' ? 'selected' : '' }}>Toutes dates</option>
                    <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>

            <div class="transition-all duration-300 transform hover:scale-105">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tri</label>
                <select name="sort"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récentes</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 w-full flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Appliquer les filtres
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des entreprises avec animations -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Entreprise
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Propriétaire
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date de création
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Traitée par
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($companies as $index => $company)
                    <tr class="hover:bg-gray-50 transition-all duration-300 animate-fade-in-down"
                        style="animation-delay: {{ $index * 0.05 }}s">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    @if ($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}"
                                            class="h-8 w-8 rounded-full">
                                    @else
                                        <span
                                            class="text-lg font-bold text-gray-500">{{ substr($company->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $company->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $company->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $company->owner->name }}</div>
                            <div class="text-sm text-gray-500">{{ $company->owner->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $company->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-all duration-300
                            {{ $company->status === 'approved'
                                ? 'bg-green-100 text-green-800'
                                : ($company->status === 'rejected'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $company->status === 'approved' ? 'Approuvée' : ($company->status === 'rejected' ? 'Rejetée' : 'En attente') }}
                            </span>
                            @if ($company->status === 'rejected' && $company->rejection_reason)
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate"
                                    title="{{ $company->rejection_reason }}">
                                    <strong>Raison :</strong> {{ Str::limit($company->rejection_reason, 50) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if ($company->processedBy)
                                <div class="flex items-center">
                                    <span
                                        class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 mr-2">
                                        {{ substr($company->processedBy->name ?? 'Admin', 0, 1) }}
                                    </span>
                                    <span>{{ $company->processedBy->name ?? 'Admin système' }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">Non traité</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="openStatusModal('{{ $company->id }}', '{{ $company->status }}')"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </button>
                                @if ($company->status === 'pending')
                                    <form action="{{ route('admin.companies.approve', $company) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="text-green-600 hover:text-green-900 transition-colors duration-300 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approuver
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal('{{ $company->id }}')"
                                        class="text-red-600 hover:text-red-900 transition-colors duration-300 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rejeter
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if ($companies->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-lg font-medium">Aucune entreprise trouvée</p>
                                <p class="text-sm mt-1">Modifiez vos filtres ou ajoutez de nouvelles entreprises</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $companies->links() }}
        </div>
    </div>
</div>

<!-- Modal pour modifier le statut avec animations -->
<div id="statusModal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md transform transition-transform duration-300 scale-95">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Modifier le statut</h3>
            <button onclick="closeModal('statusModal')"
                class="text-gray-400 hover:text-gray-600 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="statusForm" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div class="transition-all duration-300 transform hover:scale-105">
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="statusSelect"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300">
                        <option value="pending">En attente</option>
                        <option value="approved">Approuvée</option>
                        <option value="rejected">Rejetée</option>
                    </select>
                </div>
                <div id="reasonField" class="hidden transition-all duration-300">
                    <label class="block text-sm font-medium text-gray-700">Raison du rejet</label>
                    <textarea name="rejection_reason" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                <button type="button" onclick="closeModal('statusModal')"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all duration-300">
                    Annuler
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-md shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour rejeter une entreprise avec animations -->
<div id="rejectModal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md transform transition-transform duration-300 scale-95">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Rejeter une entreprise</h3>
            <button onclick="closeModal('rejectModal')"
                class="text-gray-400 hover:text-gray-600 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Cette action est irréversible. Le propriétaire sera notifié du rejet.
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">Veuillez indiquer la raison du rejet :</p>
                <div class="transition-all duration-300 transform hover:scale-105">
                    <textarea name="rejection_reason" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300"
                        required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                <button type="button" onclick="closeModal('rejectModal')"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all duration-300">
                    Annuler
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-md shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    Confirmer le rejet
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 0.5s ease-out forwards;
    }
</style>

<script>
    function openStatusModal(companyId, currentStatus) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        const statusSelect = document.getElementById('statusSelect');
        const reasonField = document.getElementById('reasonField');

        form.action = `/admin/companies/${companyId}/update-status`;
        statusSelect.value = currentStatus;

        // Afficher le champ raison si le statut actuel est rejeté
        reasonField.classList.toggle('hidden', statusSelect.value !== 'rejected');

        // Changer la visibilité du champ raison quand le statut change
        statusSelect.addEventListener('change', () => {
            reasonField.classList.toggle('hidden', statusSelect.value !== 'rejected');
        });

        // Afficher le modal avec animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('.bg-white').classList.add('scale-100');
        }, 10);
    }

    function openRejectModal(companyId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');

        form.action = `/admin/companies/${companyId}/reject`;

        // Afficher le modal avec animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('.bg-white').classList.add('scale-100');
        }, 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);

        // Cacher le modal avec animation
        modal.classList.remove('opacity-100');
        modal.querySelector('.bg-white').classList.remove('scale-100');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection

@section('sidebar-active', 'companies')
