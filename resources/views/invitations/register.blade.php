<!-- resources/views/invitations/register.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Créer un compte</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p>Vous avez été invité à rejoindre ImmoConnect en tant que <strong>{{ $invitation->role->name }}</strong>.</p>
                        @if($invitation->company)
                            <p>Vous rejoindrez l'entreprise <strong>{{ $invitation->company->name }}</strong>.</p>
                        @endif
                        <p>Veuillez compléter votre inscription ci-dessous.</p>
                    </div>

                    <form method="POST" action="{{ route('invitations.register.store', $invitation->token) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control" value="{{ $invitation->email }}" disabled>
                            <div class="form-text">Cette adresse email a été spécifiée dans l'invitation.</div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Créer mon compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection