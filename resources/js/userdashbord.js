// Importations FullCalendar
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';

// Rendre disponible globalement
window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    timeGridPlugin,
    interactionPlugin,
    listPlugin
};

// Gestion du menu mobile avec animation
document.getElementById('menuBtn').addEventListener('click', function() {
    const sidebar = document.querySelector('aside');
    sidebar.classList.toggle('translate-x-[-100%]');
    sidebar.classList.toggle('shadow-2xl');
    
    // Animation douce
    if (sidebar.classList.contains('translate-x-[-100%]')) {
        sidebar.classList.remove('animate-slide-in');
        sidebar.classList.add('animate-slide-out');
    } else {
        sidebar.classList.remove('animate-slide-out');
        sidebar.classList.add('animate-slide-in');
    }
});

// Gestion des liens de navigation avec effets visuels
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        // Empêche le rechargement si c'est un lien "#"
        if (this.getAttribute('href') === '#') {
            e.preventDefault();
        }
        
        // Retire les styles actifs de tous les liens
        document.querySelectorAll('.nav-link').forEach(item => {
            item.classList.remove(
                'bg-blue-600', 
                'text-white',
                'shadow-md',
                'translate-x-2',
                'border-l-4',
                'border-blue-400'
            );
            item.classList.add('text-gray-700', 'hover:text-blue-600');
        });
        
        // Ajoute les styles actifs au lien cliqué
        this.classList.add(
            'bg-blue-600',
            'text-white',
            'shadow-md',
            'translate-x-2',
            'border-l-4',
            'border-blue-400'
        );
        this.classList.remove('text-gray-700', 'hover:text-blue-600');
        
        // Animation au clic
        this.classList.add('animate-pulse');
        setTimeout(() => {
            this.classList.remove('animate-pulse');
        }, 300);
    });
});

// Initialisation des éléments de carte
const addressInput = document.getElementById('address');
const cityInput = document.getElementById('city');
const postalCodeInput = document.getElementById('postal_code');
const countryInput = document.getElementById('country');
const latitudeInput = document.getElementById('latitude');
const longitudeInput = document.getElementById('longitude');
const getAddressButton = document.getElementById('get-address');
let map, marker;

// Configuration de la carte Leaflet
function initMap() {
    // Centre sur Abidjan par défaut
    map = L.map('map').setView([5.30966, -4.01266], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Création d'un marqueur personnalisé
    const icon = L.divIcon({
        html: '<i class="fas fa-map-marker-alt text-3xl text-red-600"></i>',
        className: 'bg-transparent border-none'
    });

    marker = L.marker([5.30966, -4.01266], {
        draggable: true,
        icon: icon
    }).addTo(map);

    // Gestion du déplacement du marqueur
    marker.on('dragend', function(e) {
        const latlng = e.target.getLatLng();
        updateFormFromCoordinates(latlng.lat, latlng.lng);
    });
}

// Mise à jour du formulaire depuis les coordonnées
async function updateFormFromCoordinates(lat, lng) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
        const data = await response.json();
        
        if (data.address) {
            const addr = data.address;
            
            // Mise à jour des champs du formulaire
            addressInput.value = data.display_name || '';
            cityInput.value = addr.city || addr.town || addr.village || '';
            postalCodeInput.value = addr.postcode || '';
            countryInput.value = addr.country || '';
            latitudeInput.value = lat;
            longitudeInput.value = lng;
            
            // Mise à jour du marqueur
            marker.setLatLng([lat, lng])
                .bindPopup(`<div class="p-2">
                    <p class="font-bold">${data.display_name || 'Localisation'}</p>
                    <p class="text-sm">Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</p>
                </div>`)
                .openPopup();
        }
    } catch (error) {
        console.error("Erreur de géocodage inverse:", error);
    }
}

// Géolocalisation de l'utilisateur
function geolocateUser() {
    if (navigator.geolocation) {
        getAddressButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Localisation...';
        getAddressButton.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                updateFormFromCoordinates(lat, lng);
                
                // Recentrer la carte
                map.setView([lat, lng], 15);
                
                // Réinitialiser le bouton
                resetAddressButton();
            },
            error => {
                console.error("Erreur de géolocalisation:", error);
                getAddressButton.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> Erreur';
                getAddressButton.classList.add('bg-red-500');
                setTimeout(resetAddressButton, 2000);
            }
        );
    } else {
        alert("La géolocalisation n'est pas supportée par votre navigateur.");
    }
}

// Recherche d'adresse depuis le champ texte
async function searchAddress() {
    const address = addressInput.value.trim();
    if (address.length < 3) return;
    
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&addressdetails=1`);
        const data = await response.json();
        
        if (data && data.length > 0) {
            const firstResult = data[0];
            const lat = parseFloat(firstResult.lat);
            const lng = parseFloat(firstResult.lon);
            
            updateFormFromCoordinates(lat, lng);
            map.setView([lat, lng], 15);
        }
    } catch (error) {
        console.error("Erreur de recherche d'adresse:", error);
    }
}

function resetAddressButton() {
    getAddressButton.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i> Localiser';
    getAddressButton.disabled = false;
    getAddressButton.classList.remove('bg-red-500');
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Événements
    getAddressButton.addEventListener('click', geolocateUser);
    addressInput.addEventListener('change', searchAddress);
    
    // Délai pour éviter les requêtes à chaque frappe
    let timeout;
    addressInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(searchAddress, 800);
    });
});

// Gestion du changement de type de propriété
const typeSelect = document.getElementById('type');
const squareLabel = document.getElementById('square-label');
const squareInput = document.getElementById('square');

typeSelect.addEventListener('change', function() {
    const isVente = this.value === 'vente';
    
    // Transition douce pour l'affichage
    if (isVente) {
        squareLabel.classList.remove('hidden');
        squareLabel.classList.add('animate-fade-in');
        squareInput.classList.remove('hidden');
        squareInput.classList.add('animate-fade-in');
        squareInput.required = true;
    } else {
        squareLabel.classList.add('hidden');
        squareInput.classList.add('hidden');
        squareInput.required = false;
    }
});

// Événements initiaux
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Délai pour éviter les recherches à chaque touche
    let searchTimeout;
    addressInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(searchAddress, 800);
    });
    
    getAddressButton.addEventListener('click', geolocateUser);

    const elements = document.querySelectorAll('.animate-fade-in');
    elements.forEach((el, index) => {
      setTimeout(() => {
        el.classList.add('opacity-100', 'translate-y-0');
      }, 100 * index);
    });
});

