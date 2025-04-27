@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Nouvelle invitation</h1>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('invitations.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="role_id" class="form-label">Rôle <span class="text-danger">*</span></label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                @if($companies->count() > 0)
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Entreprise</label>
                        <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id">
                            <option value="">Aucune entreprise</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Laissez vide si l'invitation n'est pas liée à une entreprise.</div>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('invitations.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Envoyer l'invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection