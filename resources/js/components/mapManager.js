// resources/js/components/mapManager.js
import L from 'leaflet'

// resources/js/components/mapManager.js
export default function() {
    return {
        map: null,
        markers: [],
        initMap() {
            this.map = L.map('main-map').setView([5.30415310, -4.24132960], 13)
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(this.map)
        },
        addMarkers(properties) {
            this.clearMarkers()
            properties.forEach(prop => {
                if (!prop.latitude || !prop.longitude) {
                    console.error('Coordonnées manquantes pour:', prop)
                    return
                }
                
                const marker = L.marker([parseFloat(prop.latitude), parseFloat(prop.longitude)], {
                    icon: L.divIcon({
                        html: `<div class="map-marker">${prop.price_formatted}</div>`,
                        className: 'custom-marker'
                    })
                }).addTo(this.map)
                this.markers.push(marker)
            })
        },
        clearMarkers() {
            this.markers.forEach(marker => marker.remove())
            this.markers = []
        }
    }
}