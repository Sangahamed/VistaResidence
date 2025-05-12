@php
    $value = fn($field, $default = '') => old($field, $property->$field ?? $default);
    $isEdit = isset($property);
@endphp

<div class="row">
    <div class="col-md-8">
        {{-- Informations de base --}}
        <x-property.form-section title="Informations de base">
            <x-property.input name="title" label="Titre" required :value="$value('title')" />
            <x-property.textarea name="description" label="Description" :value="$value('description')" />

            <div class="row">
                <x-property.select name="type" label="Type de bien" required :options="[
                    'apartment' => 'Appartement',
                    'house' => 'Maison',
                    'villa' => 'Villa',
                    'land' => 'Terrain',
                    'commercial' => 'Local commercial',
                    'office' => 'Bureau',
                ]" :selected="$value('type')" class="col-md-6" />

                <x-property.select name="status" label="Statut" required :options="[
                    'for_sale' => 'À vendre',
                    'for_rent' => 'À louer',
                    'sold' => 'Vendu',
                    'rented' => 'Loué',
                ]" :selected="$value('status')" class="col-md-6" />
            </div>

            <x-property.input name="price" label="Prix (€)" type="number" step="0.01" required :value="$value('price')" icon="€" />
        </x-property.form-section>

        {{-- Localisation --}}
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-2">Localisation</h3>
            
            @livewire('address-autocomplete', [
                'address' => $property->address ?? '',
                'street' => $property->street ?? '',
                'city' => $property->city ?? '',
                'postalCode' => $property->postal_code ?? '',
                'country' => $property->country ?? '',
                'latitude' => $property->latitude ?? null,
                'longitude' => $property->longitude ?? null,
            ])
        </div>

        {{-- Caractéristiques --}}
        <x-property.form-section title="Caractéristiques">
            <div class="row">
                <x-property.input name="bedrooms" label="Chambres" type="number" class="col-md-6" :value="$value('bedrooms')" />
                <x-property.input name="bathrooms" label="Salles de bain" type="number" class="col-md-6" :value="$value('bathrooms')" />
            </div>

            <div class="row">
                <x-property.input name="area" label="Surface (m²)" type="number" step="0.01" class="col-md-6" :value="$value('area')" />
                <x-property.input name="year_built" label="Année de construction" type="number" class="col-md-6" :value="$value('year_built')" />
            </div>

            @php
                $featuresList = [
                    'garage' => 'Garage', 'parking' => 'Parking', 'garden' => 'Jardin',
                    'terrace' => 'Terrasse', 'balcony' => 'Balcon', 'pool' => 'Piscine',
                    'elevator' => 'Ascenseur', 'air_conditioning' => 'Climatisation',
                    'heating' => 'Chauffage', 'security_system' => 'Sécurité',
                    'storage' => 'Stockage', 'furnished' => 'Meublé',
                ];
                $featuresSelected = old('features', $property->features ?? []);
            @endphp

            <div class="row">
                @foreach ($featuresList as $key => $label)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="feature_{{ $key }}" name="features[]" value="{{ $key }}"
                                {{ in_array($key, $featuresSelected) ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_{{ $key }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                    {{ old('is_featured', $property->is_featured ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">
                    Mettre en avant cette propriété
                </label>
            </div>
        </x-property.form-section>

        {{-- Médias (désactivé si Livewire utilisé) --}}
        @if(!$isEdit)
        <x-property.form-section title="Médias">
            <x-property.input name="images[]" label="Images" type="file" multiple accept="image/*" />
            <x-property.input name="videos[]" label="Vidéos" type="file" multiple accept="video/*" />
        </x-property.form-section>
        @endif
    </div>

    <div class="col-md-4">
        {{-- Entreprise --}}
        @if(auth()->user()->companies->count() > 0)
        <x-property.form-section title="Entreprise">
            <label for="company_id" class="form-label">Associer à une entreprise</label>
            <select class="form-select" name="company_id" id="company_id">
                <option value="">Aucune entreprise</option>
                @foreach(auth()->user()->companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </x-property.form-section>
        @endif
    </div>
</div>
