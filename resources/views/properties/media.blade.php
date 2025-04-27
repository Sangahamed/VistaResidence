@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Médias de la propriété : {{ $property->title }}</h2>

    <livewire:property-media-manager :property="$property" />

    <a href="{{ route('properties.show', $property) }}" class="btn btn-success mt-4">
        ✅ Terminer et voir la fiche
    </a>
</div>
@endsection
