<div>
    <!-- Card principale -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Gestion des Médias</h5>
        </div>
        <div class="card-body">
            <!-- Onglets pour images et vidéos -->
            <ul class="nav nav-tabs mb-3" id="mediaTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab" aria-controls="images" aria-selected="true">Images ({{ count($images) }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos" type="button" role="tab" aria-controls="videos" aria-selected="false">Vidéos ({{ count($videos) }})</button>
                </li>
            </ul>

            <div class="tab-content" id="mediaTabContent">
                <!-- Onglet Images -->
                <div class="tab-pane fade show active" id="images" role="tabpanel" aria-labelledby="images-tab">
                    <!-- Affichage des images existantes -->
                    @if(!empty($images) && count($images) > 0)
                        <div class="row mb-4">
                            @foreach($images as $index => $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ Storage::url($image['path']) }}" class="card-img-top" alt="Image">
                                        <div class="card-body p-2 text-center">
                                            <button class="btn btn-sm btn-outline-danger" wire:click="removeMedia('image', {{ $index }})">
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucune image n'a été ajoutée à cette propriété.
                        </div>
                    @endif

                    <!-- Upload de nouvelles images -->
                    <div class="mb-3">
                        <label for="newImages" class="form-label">Ajouter des images</label>
                        <input type="file" class="form-control" id="newImages" wire:model="newImages" multiple accept="image/*">
                        @error('newImages.*') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Bouton d'upload -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" wire:click="saveMedia" wire:loading.attr="disabled">
                            <span wire:loading wire:target="saveMedia" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Télécharger les images
                        </button>
                    </div>
                </div>

                <!-- Onglet Vidéos -->
                <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="videos-tab">
                    <!-- Affichage des vidéos existantes -->
                    @if(!empty($videos) && count($videos) > 0)
                        <div class="row mb-4">
                            @foreach($videos as $index => $video)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Vidéo</h6>
                                            <p class="card-text small text-muted">{{ $video['filename'] }}</p>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ Storage::url($video['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Voir</a>
                                                <button class="btn btn-sm btn-outline-danger" wire:click="removeMedia('video', {{ $index }})">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucune vidéo n'a été ajoutée à cette propriété.
                        </div>
                    @endif

                    <!-- Upload de nouvelles vidéos -->
                    <div class="mb-3">
                        <label for="newVideos" class="form-label">Ajouter des vidéos</label>
                        <input type="file" class="form-control" id="newVideos" wire:model="newVideos" multiple accept="video/*">
                        @error('newVideos.*') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Bouton d'upload -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" wire:click="saveMedia" wire:loading.attr="disabled">
                            <span wire:loading wire:target="saveMedia" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Télécharger les vidéos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
