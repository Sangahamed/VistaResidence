@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>Nos agents immobiliers</h1>
            <p class="text-muted">Contactez directement nos agents pour toute question sur nos propriétés.</p>
        </div>
    </div>

    <div class="row mt-4">
        @foreach($agents as $agent)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ $agent->profile_photo_url }}" alt="{{ $agent->name }}" class="rounded-circle" width="100" height="100">
                        </div>
                        <h5 class="card-title">{{ $agent->name }}</h5>
                        <p class="card-text text-muted">
                            {{ $agent->account_type === 'company' ? 'Agence immobilière' : 'Agent immobilier' }}
                        </p>
                        
                        @if($agent->companies->count() > 0)
                            <p class="card-text small">
                                <strong>Agence :</strong> {{ $agent->companies->first()->name }}
                            </p>
                        @endif
                        
                        @auth
                            <a href="{{ route('chatify', ['user_id' => $agent->id]) }}" class="btn btn-primary mt-2">
                                <i class="fas fa-comment me-2"></i>Contacter
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter pour contacter
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection