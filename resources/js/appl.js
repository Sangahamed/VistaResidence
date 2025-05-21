// resources/js/app.js
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// Configuration Leaflet
import 'leaflet/dist/leaflet.css'

// Livewire sera chargé automatiquement par Laravel
document.addEventListener('livewire:init', () => {
    // Configuration des icônes Leaflet
    delete L.Icon.Default.prototype._getIconUrl
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png',
        iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png'
    })
})