import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';

// Initialize Swiper with all needed modules
Swiper.use([Navigation, Pagination, Autoplay, EffectFade]);

document.addEventListener("DOMContentLoaded", function() {
  // Initialize property sliders
  initPropertySliders();
  
  // Initialize favorite buttons
  initFavoriteButtons();
  
  // Initialize price sliders
  initPriceSliders();
  
  // Initialize lightbox
  initLightbox();
  
  // Initialize animations
  initAnimations();
});

function initPropertySliders() {
  document.querySelectorAll('.swiper').forEach(el => {
    new Swiper(el, {
      loop: true,
      effect: 'fade',
      fadeEffect: { crossFade: true },
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      on: {
        init: function() {
          this.el.classList.add('initialized');
        }
      }
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
  const minSlider = document.getElementById('price-min');
  const maxSlider = document.getElementById('price-max');
  
  if (minSlider && maxSlider) {
    const updatePriceDisplay = () => {
      document.getElementById('minPriceValue').textContent = formatPrice(minSlider.value);
      document.getElementById('maxPriceValue').textContent = formatPrice(maxSlider.value);
    };
    
    [minSlider, maxSlider].forEach(slider => {
      slider.addEventListener('input', () => {
        updatePriceDisplay();
        
        // Visual feedback
        slider.style.setProperty('--thumb-shadow', '0 0 0 4px rgba(249, 115, 22, 0.3)');
        setTimeout(() => {
          slider.style.setProperty('--thumb-shadow', 'none');
        }, 300);
      });
    });
    
    updatePriceDisplay();
  }
}

function formatPrice(value, currency = 'FCFA') {
  return new Intl.NumberFormat('fr-FR').format(value) + ' ' + currency;
}

function initLightbox() {
  // Lightbox navigation with keyboard
  document.addEventListener('keydown', function(e) {
    const lightbox = document.querySelector('.lightbox:target');
    if (!lightbox) return;
    
    if (e.key === 'Escape') {
      history.back();
    } else if (e.key === 'ArrowLeft') {
      navigateLightbox(lightbox, 'prev');
    } else if (e.key === 'ArrowRight') {
      navigateLightbox(lightbox, 'next');
    }
  });
}

function navigateLightbox(currentLightbox, direction) {
  const lightboxes = Array.from(document.querySelectorAll('.lightbox'));
  const currentIndex = lightboxes.indexOf(currentLightbox);
  
  let targetIndex;
  if (direction === 'prev') {
    targetIndex = (currentIndex - 1 + lightboxes.length) % lightboxes.length;
  } else {
    targetIndex = (currentIndex + 1) % lightboxes.length;
  }
  
  window.location.hash = lightboxes[targetIndex].id;
}

function initAnimations() {
  // Intersection Observer for scroll animations
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-fade-in');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });
  
  document.querySelectorAll('[data-animate]').forEach(el => {
    observer.observe(el);
  });
  
  // Add animation delays based on data attributes
  document.querySelectorAll('[data-delay]').forEach(el => {
    el.style.animationDelay = el.dataset.delay + 'ms';
  });
}

// Initialize star ratings
function initStarRatings(containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;
  
  container.addEventListener('click', function(e) {
    if (e.target.tagName === 'I') {
      const stars = Array.from(this.children);
      const rating = stars.indexOf(e.target) + 1;
      
      stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < rating);
        star.classList.toggle('text-gray-300', index >= rating);
        
        if (index < rating) {
          star.style.transform = 'scale(1.2)';
          setTimeout(() => {
            star.style.transform = 'scale(1)';
          }, 200);
        }
      });
      
      // Update hidden input if exists
      const ratingInput = document.getElementById('rating-input');
      if (ratingInput) {
        ratingInput.value = rating;
      }
    }
  });
  
  // Hover effect
  container.addEventListener('mouseover', function(e) {
    if (e.target.tagName === 'I') {
      const stars = Array.from(this.children);
      const hoverIndex = stars.indexOf(e.target);
      
      stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-300', index <= hoverIndex);
      });
    }
  });
  
  container.addEventListener('mouseout', function() {
    const stars = Array.from(this.children);
    const ratingInput = document.getElementById('rating-input');
    const currentRating = ratingInput ? parseInt(ratingInput.value) : 0;
    
    stars.forEach((star, index) => {
      star.classList.remove('text-yellow-300');
      star.classList.toggle('text-yellow-400', index < currentRating);
      star.classList.toggle('text-gray-300', index >= currentRating);
    });
  });
}

// Initialize all star rating components
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('[data-star-rating]').forEach(container => {
    initStarRatings(container.id);
  });
});