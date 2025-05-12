<div class="address-autocomplete">
    <div class="mb-4">
        <label for="address-input" class="block text-sm font-medium text-gray-700">Adresse</label>
        <input 
            id="address-input"
            type="text" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Saisissez une adresse"
            wire:model.defer="address"
        >
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="street" class="block text-sm font-medium text-gray-700">Rue</label>
            <input 
                id="street"
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                wire:model.defer="street"
            >
        </div>
        <div>
            <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
            <input 
                id="city"
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                wire:model.defer="city"
            >
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="postal-code" class="block text-sm font-medium text-gray-700">Code postal</label>
            <input 
                id="postal-code"
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                wire:model.defer="postalCode"
            >
        </div>
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
            <input 
                id="country"
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                wire:model.defer="country"
            >
        </div>
    </div>
    
    <input type="hidden" id="latitude" wire:model.defer="latitude">
    <input type="hidden" id="longitude" wire:model.defer="longitude">
    
    <!--[if BLOCK]><![endif]--><?php if($showMap && $latitude && $longitude): ?>
        <div id="map" class="h-64 w-full rounded-md mb-4"></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google.maps_api_key')); ?>&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
        let autocomplete;
        let map;
        let marker;
        
        function initAutocomplete() {
            // Initialiser l'autocomplétion
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('address-input'),
                { types: ['address'] }
            );
            
            autocomplete.addListener('place_changed', fillInAddress);
            
            // Initialiser la carte si des coordonnées sont disponibles
            <!--[if BLOCK]><![endif]--><?php if($latitude && $longitude): ?>
                initMap();
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        }
        
        function fillInAddress() {
            const place = autocomplete.getPlace();
            
            if (!place.geometry) {
                return;
            }
            
            let street = '';
            let city = '';
            let postalCode = '';
            let country = '';
            
            // Extraire les composants de l'adresse
            for (const component of place.address_components) {
                const componentType = component.types[0];
                
                switch (componentType) {
                    case 'street_number':
                        street = component.long_name + ' ' + street;
                        break;
                    case 'route':
                        street = street + component.long_name;
                        break;
                    case 'locality':
                        city = component.long_name;
                        break;
                    case 'postal_code':
                        postalCode = component.long_name;
                        break;
                    case 'country':
                        country = component.long_name;
                        break;
                }
            }
            
            // Envoyer les données à Livewire
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('addressSelected', {
                formatted_address: place.formatted_address,
                street: street,
                city: city,
                postal_code: postalCode,
                country: country,
                latitude: place.geometry.location.lat(),
                longitude: place.geometry.location.lng()
            });
            
            // Mettre à jour la carte
            initMap();
        }
        
        function initMap() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            if (isNaN(lat) || isNaN(lng)) {
                return;
            }
            
            const mapElement = document.getElementById('map');
            
            if (!mapElement) {
                return;
            }
            
            const position = { lat, lng };
            
            if (!map) {
                map = new google.maps.Map(mapElement, {
                    center: position,
                    zoom: <?php echo e($mapZoom); ?>

                });
            } else {
                map.setCenter(position);
            }
            
            if (marker) {
                marker.setPosition(position);
            } else {
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    draggable: true
                });
                
                // Mettre à jour les coordonnées lorsque le marqueur est déplacé
                marker.addListener('dragend', function() {
                    const position = marker.getPosition();
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('addressSelected', {
                        latitude: position.lat(),
                        longitude: position.lng()
                    });
                });
            }
        }
        
        // Écouter les événements Livewire
        document.addEventListener('livewire:load', function() {
            Livewire.on('addressReset', function() {
                if (marker) {
                    marker.setMap(null);
                    marker = null;
                }
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
</div><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/livewire/address-autocomplete.blade.php ENDPATH**/ ?>