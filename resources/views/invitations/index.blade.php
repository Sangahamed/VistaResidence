@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Invitations</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('invitations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nouvelle invitation
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($invitations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Entreprise</th>
                                <th>Statut</th>
                                <th>Date d'expiration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitation->email }}</td>
                                    <td>{{ $invitation->role->name }}</td>
                                    <td>{{ $invitation->company ? $invitation->company->name : 'N/A' }}</td>
                                    <td>
                                        @if($invitation->status === 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($invitation->status === 'accepted')
                                            <span class="badge bg-success">Acceptée</span>
                                        @elseif($invitation->status === 'declined')
                                            <span class="badge bg-danger">Refusée</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $invitation->expires_at->format('d/m/Y H:i') }}
                                        @if($invitation->hasExpired())
                                            <span class="badge bg-danger">Expirée</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invitation->status === 'pending')
                                            <form action="{{ route('invitations.resend', $invitation) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-paper-plane"></i> Renvoyer
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('invitations.destroy', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette invitation ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $invitations->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Aucune invitation n'a été envoyée pour le moment.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection