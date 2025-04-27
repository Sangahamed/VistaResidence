@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier la propriété</h2>

    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('properties.partials.form', ['property' => $property])


        {{-- Images existantes --}}
    <h5>Images existantes</h5>
    <div class="row">
        @foreach ($property->images ?? [] as $index => $image)
            <div class="col-md-3">
                <img src="{{ Storage::url($image['path']) }}" class="img-fluid mb-2" alt="image">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $index }}">
                    <label class="form-check-label">Supprimer</label>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Vidéos existantes --}}
    <h5>Vidéos existantes</h5>
    <div class="row">
        @foreach ($property->videos ?? [] as $index => $video)
            <div class="col-md-4 mb-3">
                <video width="100%" controls>
                    <source src="{{ Storage::url($video['path']) }}" type="video/mp4">
                    Votre navigateur ne prend pas en charge la lecture vidéo.
                </video>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="delete_videos[]" value="{{ $index }}">
                    <label class="form-check-label">Supprimer</label>
                </div>
            </div>
        @endforeach
    </div>

    <hr>

    {{-- Ajouter nouvelles images --}}
    <div class="mb-3">
        <label for="new_images" class="form-label">Ajouter des images</label>
        <input type="file" name="new_images[]" class="form-control" multiple accept="image/*">
    </div>

    {{-- Ajouter nouvelles vidéos --}}
    <div class="mb-3">
        <label for="new_videos" class="form-label">Ajouter des vidéos</label>
        <input type="file" name="new_videos[]" class="form-control" multiple accept="video/*">
    </div>


    <button type="submit" class="btn btn-primary">
        <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" role="status"></span>
        💾 Mettre à jour
    </button>
    
    <script>
    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
    </script>
    </form>
</div>
@endsection
