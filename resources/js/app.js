import Swiper from "swiper";
import { Navigation, Pagination, Autoplay, EffectFade } from "swiper/modules";
import Toastify from "toastify-js";
import NProgress from "nprogress";
import "nprogress/nprogress.css";

// Initialize Swiper with all needed modules
Swiper.use([Navigation, Pagination, Autoplay, EffectFade]);

NProgress.configure({ showSpinner: false });

document.addEventListener("DOMContentLoaded", function () {
    // Initialize property sliders
    initPropertySliders();

    // Initialize price sliders
    initPriceSliders();

    initFavoriteButtons();

    // Initialize lightbox
    initLightbox();

    // Initialize animations
    initAnimations();

    initNotifications();
    initGeolocation();
    initLivewireScroll();

    document.querySelectorAll("[data-star-rating]").forEach((container) => {
        initStarRatings(container.id);
    });
});

function initPropertySliders() {
    document.querySelectorAll(".swiper").forEach((el) => {
        new Swiper(el, {
            loop: false,
            effect: "fade",
            fadeEffect: { crossFade: true },
            autoplay: false,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            on: {
                init: function () {
                    this.el.classList.add("initialized");
                },
            },
        });
    });
}


function initFavoriteButtons() {
  document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const icon = this.querySelector('svg');
      const isActive = this.classList.toggle('active');
      
      // Animation
      this.style.transform = 'scale(1.2)';
      setTimeout(() => {
        this.style.transform = 'scale(1)';
      }, 300);
      
      // Change icon state
      if (isActive) {
        icon.classList.add('text-red-500');
        icon.classList.remove('text-white');
      } else {
        icon.classList.remove('text-red-500');
        icon.classList.add('text-white');
      }
      
      // Save to localStorage or send to server
      const propertyId = this.dataset.propertyId;
      if (propertyId) {
        updateFavoriteStatus(propertyId, isActive);
      }
    });
  });
}


function updateFavoriteStatus(propertyId, isFavorite) {
  // Here you would typically make an AJAX call to your server
  console.log(`Property ${propertyId} favorite status: ${isFavorite}`);
  
  // For demo purposes, we'll use localStorage
  const favorites = JSON.parse(localStorage.getItem('propertyFavorites') || "{}");
  favorites[propertyId] = isFavorite;
  localStorage.setItem('propertyFavorites', JSON.stringify(favorites));
}

function initPriceSliders() {
    const minSlider = document.getElementById("price-min");
    const maxSlider = document.getElementById("price-max");

    if (minSlider && maxSlider) {
        const updatePriceDisplay = () => {
            document.getElementById("minPriceValue").textContent = formatPrice(
                minSlider.value
            );
            document.getElementById("maxPriceValue").textContent = formatPrice(
                maxSlider.value
            );
        };

        [minSlider, maxSlider].forEach((slider) => {
            slider.addEventListener("input", () => {
                updatePriceDisplay();

                // Visual feedback
                slider.style.setProperty(
                    "--thumb-shadow",
                    "0 0 0 4px rgba(249, 115, 22, 0.3)"
                );
                setTimeout(() => {
                    slider.style.setProperty("--thumb-shadow", "none");
                }, 300);
            });
        });

        updatePriceDisplay();
    }
}

function formatPrice(value, currency = "FCFA") {
    return new Intl.NumberFormat("fr-FR").format(value) + " " + currency;
}

function initLightbox() {
    // Lightbox navigation with keyboard
    document.addEventListener("keydown", function (e) {
        const lightbox = document.querySelector(".lightbox:target");
        if (!lightbox) return;

        if (e.key === "Escape") {
            history.back();
        } else if (e.key === "ArrowLeft") {
            navigateLightbox(lightbox, "prev");
        } else if (e.key === "ArrowRight") {
            navigateLightbox(lightbox, "next");
        }
    });
}

function navigateLightbox(currentLightbox, direction) {
    const lightboxes = Array.from(document.querySelectorAll(".lightbox"));
    const currentIndex = lightboxes.indexOf(currentLightbox);

    let targetIndex;
    if (direction === "prev") {
        targetIndex =
            (currentIndex - 1 + lightboxes.length) % lightboxes.length;
    } else {
        targetIndex = (currentIndex + 1) % lightboxes.length;
    }

    window.location.hash = lightboxes[targetIndex].id;
}

function initAnimations() {
    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate-fade-in");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 }
    );

    document.querySelectorAll("[data-animate]").forEach((el) => {
        observer.observe(el);
    });

    // Add animation delays based on data attributes
    document.querySelectorAll("[data-delay]").forEach((el) => {
        el.style.animationDelay = el.dataset.delay + "ms";
    });
}

// Initialize star ratings
function initStarRatings(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.addEventListener("click", function (e) {
        if (e.target.tagName === "I") {
            const stars = Array.from(this.children);
            const rating = stars.indexOf(e.target) + 1;

            stars.forEach((star, index) => {
                star.classList.toggle("text-yellow-400", index < rating);
                star.classList.toggle("text-gray-300", index >= rating);

                if (index < rating) {
                    star.style.transform = "scale(1.2)";
                    setTimeout(() => {
                        star.style.transform = "scale(1)";
                    }, 200);
                }
            });

            // Update hidden input if exists
            const ratingInput = document.getElementById("rating-input");
            if (ratingInput) {
                ratingInput.value = rating;
            }
        }
    });

    // Hover effect
    container.addEventListener("mouseover", function (e) {
        if (e.target.tagName === "I") {
            const stars = Array.from(this.children);
            const hoverIndex = stars.indexOf(e.target);

            stars.forEach((star, index) => {
                star.classList.toggle("text-yellow-300", index <= hoverIndex);
            });
        }
    });

    container.addEventListener("mouseout", function () {
        const stars = Array.from(this.children);
        const ratingInput = document.getElementById("rating-input");
        const currentRating = ratingInput ? parseInt(ratingInput.value) : 0;

        stars.forEach((star, index) => {
            star.classList.remove("text-yellow-300");
            star.classList.toggle("text-yellow-400", index < currentRating);
            star.classList.toggle("text-gray-300", index >= currentRating);
        });
    });
}

function initGeolocation() {
    // Vérifier si la position est déjà en session
    if (localStorage.getItem('userPosition')) {
        return;
    }

    // Essayer la géolocalisation HTML5
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                    source: 'browser'
                };
                storePosition(pos);
            },
            (error) => {
                getIpBasedLocation();
            },
            { maximumAge: 600000, timeout: 5000 } // 10min cache
        );
    } else {
        getIpBasedLocation();
    }
}

function getIpBasedLocation() {
    fetch('/api/get-location')
        .then(response => response.json())
        .then(data => {
            if (data.lat && data.lng) {
                storePosition(data);
            }
        });
}

function storePosition(position) {
    localStorage.setItem('userPosition', JSON.stringify(position));
    
    fetch('/store-position', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(position)
    });
}



function initLivewireScroll() {
    window.addEventListener("scroll", () => {
        if (
            window.innerHeight + window.scrollY >=
            document.body.offsetHeight - 500
        ) {
            Livewire.dispatch("loadMore");
        }
    });
}

document.addEventListener("livewire:load", () => {
    Livewire.hook("message.processed", () => {
        // Animation sur chaque carte qui n'a pas encore été animée
        document.querySelectorAll(".property-card").forEach((el) => {
            if (!el.classList.contains("livewire-animated")) {
                el.classList.add("animate-fade-in-up", "livewire-animated");
                setTimeout(() => {
                    el.classList.remove("animate-fade-in-up");
                }, 700);
            }
        });
    });
});

function initNotifications() {
     if (typeof window.Echo === 'undefined' || !window.authUserId) {
            return;
        }
    
        window.Echo.private(`App.Models.User.${window.authUserId}`)
            .notification((notification) => {
                Livewire.emit("notificationReceived");
                showNotificationToast(notification);
            });

    // Gestion des clics sur les notifications pour les marquer comme lues
    document.querySelectorAll(".notification-link").forEach((link) => {
        link.addEventListener("click", function (e) {
            const notificationId = this.dataset.notificationId;
            if (notificationId) {
                markAsRead(notificationId);
            }
        });
    });

    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                "Content-Type": "application/json",
            },
        }).then((response) => {
            if (response.ok) {
                const notificationElement = document.querySelector(
                    `[data-notification-id="${notificationId}"]`
                );
                if (notificationElement) {
                    notificationElement.classList.remove("unread");
                    updateUnreadCount();
                }
            }
        });
    }

    function updateUnreadCount() {
        fetch("/notifications/count")
            .then((response) => response.json())
            .then((data) => {
                const counter = document.querySelector(".notification-counter");
                if (counter) {
                    counter.textContent = data.count;
                    counter.style.display = data.count === 0 ? "none" : "block";
                }
            });
    }
}

function showNotificationToast(notification) {
    Toastify({
        text: `${notification.title}\n${notification.message}`,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#f97316",
        stopOnFocus: true,
    }).showToast();
}
