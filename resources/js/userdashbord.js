document.getElementById('menuBtn').addEventListener('click', function () {
    const sidebar = document.querySelector('aside');
    sidebar.classList.toggle('hidden');
    sidebar.classList.toggle('animate-slide-in');
});



document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function () {
        // Retirer la classe active de tous les liens
        document.querySelectorAll('.nav-link').forEach(item => {
            item.classList.remove('bg-blue-500', 'text-white', 'active');
            item.classList.add('text-gray-700');
            item.classList.remove('animate-scale-up');
        });
        // Ajouter la classe active au lien cliqué
        this.classList.add('bg-blue-500', 'text-white', 'active');
        this.classList.remove('text-gray-700');
        this.classList.add('animate-scale-up');
    });
});



let files = [];

function handleChange() {
    const input = document.getElementById('image');
    const previewContainer = document.getElementById('preview-container');

    Array.from(input.files).forEach(file => {
        if (!files.includes(file) && files.length < 20) {
            files.push(file);
            const reader = new FileReader();

            reader.onload = function (event) {
                const preview = document.createElement('div');
                preview.classList.add('preview-item', 'rounded-md',
                    'shadow', 'overflow-hidden', 'bg-gray-50',
                    'dark:bg-gray-800', 'text-gray-400', 'p-2',
                    'text-center', 'small', 'w-auto',
                    'max-h-60', 'mr-4', 'mb-4', 'relative');

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = file.name;
                    preview.appendChild(img);
                }

                const removeButton = document.createElement(
                    'button');
                removeButton.classList.add('remove-button',
                    'bg-red-500', 'hover:bg-red-700',
                    'text-white', 'rounded-md', 'px-2', 'py-1',
                    'text-sm', 'absolute', 'top-2', 'right-2');
                removeButton.innerHTML =
                    '<i class="fas fa-trash-alt"></i>';
                removeButton.onclick = function () {
                    previewContainer.removeChild(preview);
                    files = files.filter(f => f !== file);
                };
                preview.appendChild(removeButton);

                previewContainer.appendChild(preview);
            };

            reader.readAsDataURL(file);
        }
    });
}

function limitFiles() {
    const input = document.getElementById('image');
    if (input.files.length > 20) {
        alert('Vous ne pouvez télécharger que 20 fichiers maximum.');
        input.value = '';
    }
}

function checkFileSize() {
    const input = document.getElementById('image');
    Array.from(input.files).forEach(file => {
        if (file.size > 20 * 1024 * 1024) {
            alert('La taille du fichier ' + file.name +
                ' dépasse la limite de 20MB.');
            input.value = '';
        }
    });
}

document.getElementById('image').addEventListener('change', function () {
    limitFiles();
    checkFileSize();
    handleChange();
});



const addressInput = document.getElementById('address');
const getAddressButton = document.getElementById('get-address');
let map, geocoder, marker;

// Fonction pour initialiser la carte
function initMap() {
    map = L.map('map').setView([5.30966, -4.01266],
        13); // Coordonnées d'Abidjan, Côte d'Ivoire

    // Chargement des tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Initialisation du géocodeur Nominatim d'OpenStreetMap
    geocoder = L.Control.Geocoder.nominatim();

    // Initialisation du marqueur
    marker = L.marker([5.30966, -4.01266], {
        draggable: true
    }).addTo(map);

    // Ajouter un événement de fin de déplacement au marqueur
    marker.on('dragend', function (event) {
        const latlng = event.target.getLatLng();
        geocoder.reverse(latlng, map.options.crs.scale(map.getZoom()),
            function (results) {
                const r = results[0];
                if (r) {
                    addressInput.value = r
                        .name; // Affiche l'adresse dans le champ de saisie
                    marker.bindPopup(r.name)
                        .openPopup(); // Met à jour le popup du marqueur
                }
            });
    });
}

// Fonction pour géolocaliser l'utilisateur
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const latlng = L.latLng(lat, lng);

            // Utilisation du géocodeur pour obtenir l'adresse inversement (reverse geocoding)
            geocoder.reverse(latlng, map.options.crs.scale(map
                .getZoom()), function (results) {
                const r = results[0];
                if (r) {
                    addressInput.value = r
                        .name; // Affiche l'adresse dans le champ de saisie
                    map.setView(latlng,
                        13); // Recentre la carte
                    marker.setLatLng(latlng).bindPopup(r.name)
                        .openPopup(); // Déplace le marqueur
                }
            });
        });
    }
}

// Fonction pour géocoder l'adresse saisie
function geocodeAddress() {
    const address = addressInput.value;
    geocoder.geocode(address, function (results) {
        const r = results[0];
        if (r) {
            const latlng = r.center;
            map.setView(latlng, 13); // Recentre la carte
            marker.setLatLng(latlng).bindPopup(r.name)
                .openPopup(); // Déplace le marqueur
        }
    });
}

// Ajouter un événement de saisie sur le champ d'adresse
addressInput.addEventListener('input', function () {
    geocodeAddress();
});

// Lorsque l'utilisateur clique pour récupérer l'adresse actuelle
getAddressButton.addEventListener('click', function () {
    geolocate(); // Géolocalisation de l'utilisateur
});

// Initialisation de la carte, de l'autocomplétion et de la géolocalisation après le chargement de la page
document.addEventListener('DOMContentLoaded', function () {
    initMap(); // Initialisation de la carte
});




const typeSelect = document.getElementById('type');
const squareLabel = document.getElementById('square-label');
const squareInput = document.getElementById('square');

typeSelect.addEventListener('change', function () {
    if (typeSelect.value === 'vente') {
        squareLabel.style.display = 'block';
        squareInput.style.display = 'block';
        squareInput.required = true;
    } else {
        squareLabel.style.display = 'none';
        squareInput.style.display = 'none';
        squareInput.required = false;
    }
});
