// Lightbox améliorée avec navigation fluide
class PropertyLightbox {
    constructor() {
        this.lightboxes = document.querySelectorAll('.lightbox');
        this.init();
    }

    init() {
        this.lightboxes.forEach(lb => {
            // Fermeture au clic sur le fond
            lb.addEventListener('click', (e) => {
                if (e.target === lb || e.target.classList.contains('lightbox-close')) {
                    this.closeLightbox(lb);
                }
            });

            // Navigation
            const prevBtn = lb.querySelector('.lightbox-prev');
            const nextBtn = lb.querySelector('.lightbox-next');

            if (prevBtn) prevBtn.addEventListener('click', (e) => e.stopPropagation());
            if (nextBtn) nextBtn.addEventListener('click', (e) => e.stopPropagation());
        });

        // Gestion des touches clavier
        document.addEventListener('keydown', (e) => {
            const visibleLightbox = document.querySelector('.lightbox:target');
            if (!visibleLightbox) return;

            if (e.key === 'Escape') {
                this.closeLightbox(visibleLightbox);
            } else if (e.key === 'ArrowLeft') {
                this.navigateTo(visibleLightbox, 'prev');
            } else if (e.key === 'ArrowRight') {
                this.navigateTo(visibleLightbox, 'next');
            }
        });
    }

    closeLightbox(lightbox) {
        lightbox.style.animation = 'fadeOut 0.3s ease forwards';
        setTimeout(() => {
            window.location.hash = '';
        }, 250);
    }

    navigateTo(lightbox, direction) {
        const currentId = lightbox.id;
        const lightboxIds = Array.from(this.lightboxes).map(lb => lb.id);
        const currentIndex = lightboxIds.indexOf(currentId);
        
        let targetIndex;
        if (direction === 'prev') {
            targetIndex = (currentIndex - 1 + lightboxIds.length) % lightboxIds.length;
        } else {
            targetIndex = (currentIndex + 1) % lightboxIds.length;
        }
        
        window.location.hash = lightboxIds[targetIndex];
    }
}

// Système d'avis amélioré
class PropertyReviews {
    constructor() {
        this.reviews = [];
        this.currentRating = 0;
        this.init();
    }

    init() {
        this.setupStarRating();
        this.setupReviewForm();
        this.loadInitialReviews();
    }

    setupStarRating() {
        const container = document.getElementById('starRating');
        if (!container) return;

        // Créer les étoiles si elles n'existent pas
        if (container.children.length === 0) {
            container.innerHTML = Array(5).fill('<i class="ri-star-fill text-2xl text-gray-300 cursor-pointer transition-colors duration-200"></i>').join('');
        }

        container.addEventListener('click', (e) => {
            if (e.target.tagName === 'I') {
                const stars = Array.from(container.children);
                this.currentRating = stars.indexOf(e.target) + 1;
                
                stars.forEach((star, index) => {
                    star.classList.toggle('text-yellow-400', index < this.currentRating);
                    star.classList.toggle('text-gray-300', index >= this.currentRating);
                    
                    // Animation
                    if (index < this.currentRating) {
                        star.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            star.style.transform = 'scale(1)';
                        }, 200);
                    }
                });
            }
        });

        // Effet hover
        container.addEventListener('mouseover', (e) => {
            if (e.target.tagName === 'I') {
                const stars = Array.from(container.children);
                const hoverIndex = stars.indexOf(e.target);
                
                stars.forEach((star, index) => {
                    star.classList.toggle('text-yellow-300', index <= hoverIndex && this.currentRating <= index);
                });
            }
        });

        container.addEventListener('mouseout', () => {
            const stars = Array.from(container.children);
            stars.forEach((star, index) => {
                star.classList.remove('text-yellow-300');
                star.classList.toggle('text-yellow-400', index < this.currentRating);
                star.classList.toggle('text-gray-300', index >= this.currentRating);
            });
        });
    }

    setupReviewForm() {
        const form = document.getElementById('reviewForm');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const content = document.getElementById('reviewContent').value.trim();
            if (!content || this.currentRating === 0) return;

            const newReview = {
                author: 'Vous',
                avatar: 'https://ui-avatars.com/api/?name=Vous&background=random',
                rating: this.currentRating,
                date: new Date().toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }),
                content: content
            };

            this.addReview(newReview);
            form.reset();
            
            // Reset stars
            this.currentRating = 0;
            const stars = Array.from(document.getElementById('starRating').children);
            stars.forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });

            // Feedback visuel
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Merci !';
            submitBtn.classList.add('bg-green-500');
            setTimeout(() => {
                submitBtn.textContent = 'Envoyer un avis';
                submitBtn.classList.remove('bg-green-500');
            }, 2000);
        });
    }

    loadInitialReviews() {
        // Exemple d'avis initiaux
        const initialReviews = [
            {
                author: 'Jean Dupont',
                avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
                rating: 5,
                date: '15 mars 2023',
                content: 'Excellente propriété, très bien située. Je recommande vivement !'
            },
            {
                author: 'Marie Martin',
                avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
                rating: 4,
                date: '2 février 2023',
                content: 'Très bon rapport qualité-prix. Petit bémol pour le parking un peu juste.'
            }
        ];

        initialReviews.forEach(review => this.addReview(review));
    }

    addReview(review) {
        this.reviews.unshift(review);
        this.updateReviewDisplay();
    }

    updateReviewDisplay() {
        const reviewList = document.getElementById('reviewList');
        const reviewCount = document.getElementById('reviewCount');
        const averageRatingStars = document.getElementById('averageRatingStars');
        const averageRatingText = document.getElementById('averageRatingText');

        if (!reviewList) return;

        // Calcul de la moyenne
        const avgRating = this.reviews.reduce((sum, r) => sum + r.rating, 0) / this.reviews.length || 0;

        // Mise à jour du résumé
        reviewCount.textContent = `${this.reviews.length} Avis`;
        averageRatingStars.innerHTML = this.createStarRating(avgRating);
        averageRatingText.textContent = `${avgRating.toFixed(1)} sur 5`;

        // Affichage des avis avec animation
        reviewList.innerHTML = this.reviews.map((review, index) => `
            <div class="flex space-x-4 pb-6 border-b border-gray-200 last:border-b-0 transition-all duration-300 transform hover:scale-[1.01]"
                 style="animation: fadeInUp 0.5s ease ${index * 0.1}s forwards; opacity: 0;">
                <div class="flex-shrink-0">
                    <img src="${review.avatar}" alt="${review.author}" 
                         class="w-12 h-12 rounded-full object-cover shadow-md">
                </div>
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-lg font-semibold">${review.author}</h4>
                        <span class="text-sm text-gray-500">${review.date}</span>
                    </div>
                    <div class="flex mb-2">
                        ${this.createStarRating(review.rating)}
                    </div>
                    <p class="text-gray-600">${review.content}</p>
                </div>
            </div>
        `).join('');
    }

    createStarRating(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        return `
            ${'<i class="ri-star-fill text-yellow-400"></i>'.repeat(fullStars)}
            ${hasHalfStar ? '<i class="ri-star-half-line text-yellow-400"></i>' : ''}
            ${'<i class="ri-star-line text-gray-300"></i>'.repeat(emptyStars)}
        `;
    }
}

// Initialisation au chargement
document.addEventListener("DOMContentLoaded", () => {
    new PropertyLightbox();
    new PropertyReviews();
    
    // Ajout des animations CSS dynamiquement
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            to { opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});