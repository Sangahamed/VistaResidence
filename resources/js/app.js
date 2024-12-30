import './bootstrap';
import Swiper from 'swiper/bundle';

// Gestion des événements après le chargement du DOM
document.addEventListener("DOMContentLoaded", () => {
    const favoriteBtns = document.querySelectorAll(".favorite-btn");

    // Gestion des favoris
    if (favoriteBtns) {
        favoriteBtns.forEach((favoriteBtn) => {
            favoriteBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                favoriteBtn.classList.toggle("liked");
                favoriteBtn.querySelector("svg").classList.toggle("text-red-500");
                favoriteBtn.querySelector("svg").classList.toggle("text-white");
            });
        });
    }

    // Fonction pour formater les nombres avec des séparateurs de milliers
    function formatPrice(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    // Gestion des sliders de prix
    const minSlider = document.getElementById("price-min");
    const maxSlider = document.getElementById("price-max");
    const minPriceValue = document.getElementById("minPriceValue");
    const maxPriceValue = document.getElementById("maxPriceValue");

    function updatePriceDisplay() {
        if (minSlider && maxSlider) {
            minPriceValue.textContent = formatPrice(minSlider.value);
            maxPriceValue.textContent = formatPrice(maxSlider.value);
        }
    }

    if (minSlider && maxSlider) {
        minSlider.addEventListener("input", updatePriceDisplay);
        maxSlider.addEventListener("input", updatePriceDisplay);
        updatePriceDisplay();
    }

    // Initialisation de Swiper
    new Swiper('.swiper', {
        // Désactiver le défilement automatique
        autoplay: false,

        // Pagination
        pagination: {
            el: '.swiper-pagination',

        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // Autres options
        loop: true,
        effect: 'fade',
    });


});



