@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Mes propriétés</h1>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('properties.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Ajouter une propriété
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @forelse($properties as $property)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            @if (!empty($property->images) && count($property->images) > 0)
                                <img src="{{ Storage::url($property->images[0]['path']) }}" class="card-img-top"
                                    alt="{{ $property->title }}">
                            @else
                                <div class="bg-light text-center py-5">
                                    <i class="fas fa-home fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted">Aucune image</p>
                                </div>
                            @endif

                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-{{ $property->status === 'for_sale' ? 'danger' : 'primary' }}">
                                    {{ $property->status === 'for_sale' ? 'À vendre' : 'À louer' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $property->title }}</h5>
                            <p class="card-text text-primary fw-bold">{{ number_format($property->price, 0, ',', ' ') }} €
                            </p>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt text-muted"></i>
                                {{ $property->city }}, {{ $property->postal_code }}
                            </p>
                            <div class="d-flex justify-content-between text-muted small mb-3">
                                @if ($property->bedrooms)
                                    <span><i class="fas fa-bed"></i> {{ $property->bedrooms }} ch.</span>
                                @endif

                                @if ($property->bathrooms)
                                    <span><i class="fas fa-bath"></i> {{ $property->bathrooms }} sdb.</span>
                                @endif

                                @if ($property->area)
                                    <span><i class="fas fa-vector-square"></i> {{ $property->area }} m²</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('properties.show', $property) }}"
                                    class="btn btn-sm btn-outline-primary">Détails</a>
                                <a href="{{ route('properties.edit', $property) }}"
                                    class="btn btn-sm btn-outline-secondary">Modifier</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Vous n'avez pas encore ajouté de propriétés. <a href="{{ route('properties.create') }}">Ajouter une
                            propriété</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $properties->links() }}
        </div>
    </div>
@endsection
