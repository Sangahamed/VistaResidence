@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Demandes d'entreprises en attente</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @forelse($pendingCompanies as $company)
            <div class="p-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold">{{ $company->name }}</h3>
                        <p class="text-sm text-gray-600">Créée par: {{ $company->owner->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.companies.approve', $company) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                Approuver
                            </button>
                        </form>
                        <button onclick="showRejectForm({{ $company->id }})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            Rejeter
                        </button>
                    </div>
                </div>
                
                <!-- Formulaire de rejet (caché par défaut) -->
                <div id="reject-form-{{ $company->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                    <form action="{{ route('admin.companies.reject', $company) }}" method="POST">
                        @csrf
                        <textarea name="rejection_reason" required 
                                  class="w-full p-2 border rounded"
                                  placeholder="Raison du rejet..."></textarea>
                        <div class="mt-2 flex justify-end space-x-2">
                            <button type="button" onclick="hideRejectForm({{ $company->id }})" class="px-3 py-1 border rounded">
                                Annuler
                            </button>
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                Confirmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">
                Aucune demande en attente
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $pendingCompanies->links() }}
    </div>
</div>

<script>
    function showRejectForm(companyId) {
        document.getElementById(`reject-form-${companyId}`).classList.remove('hidden');
    }
    
    function hideRejectForm(companyId) {
        document.getElementById(`reject-form-${companyId}`).classList.add('hidden');
    }
</script>
@endsection