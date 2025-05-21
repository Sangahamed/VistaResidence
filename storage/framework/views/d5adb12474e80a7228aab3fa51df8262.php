    <div class="space-y-4">
        <div>
            <label class="block font-medium mb-1">Type de bien</label>
            <select wire:model.live="propertyType" class="w-full border rounded p-2">
                <option value="">Tous</option>
                <option value="house">Maison</option>
                <option value="apartment">Appartement</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Prix min</label>
                <input type="number" wire:model.live.debounce.500ms="minPrice" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium mb-1">Prix max</label>
                <input type="number" wire:model.live.debounce.500ms="maxPrice" class="w-full border rounded p-2">
            </div>
        </div>
    </div>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/livewire/map-filters.blade.php ENDPATH**/ ?>