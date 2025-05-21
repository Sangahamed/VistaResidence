// resources/js/map.js
import { mapManager } from './components/mapManager'

// Expose la fonction au scope global
window.mapManager = mapManager

// Initialisation après le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
  Livewire.on('mapFlyTo', (lat, lng, zoom) => {
    window.mapManager().flyTo(lat, lng, zoom)
  })
  
  Livewire.on('refreshMarkers', (properties) => {
    window.mapManager().addMarkers(properties)
  })
})