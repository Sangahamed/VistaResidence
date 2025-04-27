<!-- resources/views/dashboard/messages.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Mes messages</h1>
                <p class="text-muted">Gérez vos conversations avec les agents immobiliers et les clients.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Conversations récentes</h5>
                        <a href="{{ route('messenger') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-comments me-2"></i>Ouvrir la messagerie
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($conversations as $conversation)
                                @php
                                    $contact = \App\Models\User::find($conv->user_id);
                                @endphp
                                <a href="{{ route('chatify', ['user_id' => $conversation->user->id]) }}"
                                    class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $conversation->user->profile_photo_url }}"
                                                alt="{{ $conversation->user->name }}" class="rounded-circle me-3"
                                                width="50" height="50">
                                            <div>
                                                <h6 class="mb-1">{{ $conversation->user->name }}</h6>
                                                <p class="mb-1 text-muted small">
                                                    {{ Str::limit($conversation->lastMessage->body, 50) }}</p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small
                                                class="text-muted">{{ $conversation->lastMessage->created_at->diffForHumans() }}</small>
                                            @if ($conversation->unreadCount > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill ms-2">{{ $conversation->unreadCount }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <h5>Aucune conversation</h5>
                                    <p class="text-muted">Vous n'avez pas encore de conversations.</p>
                                    <a href="{{ route('messages.agents') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-user-tie me-2"></i>Contacter un agent
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
