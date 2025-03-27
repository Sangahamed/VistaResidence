document.addEventListener("DOMContentLoaded", () => {
    // Sélectionner tous les éléments avec la classe 'lightbox'
    document.querySelectorAll('.lightbox').forEach(lightbox => {
        // Ajouter un événement de clic à chaque lightbox
        lightbox.addEventListener('click', function (e) {
            // Vérifier si l'élément cliqué est la lightbox elle-même
            if (e.target === this) {
                // Ajouter la classe d'animation pour le fondu
                this.classList.add('animate-fade-out');
                // Utiliser setTimeout pour attendre la fin de l'animation avant de masquer l'élément
                setTimeout(() => {
                    this.style.display = 'none';
                    // Retirer la classe d'animation pour pouvoir la réutiliser
                    this.classList.remove('animate-fade-out');
                }, 500); // Durée de l'animation en millisecondes
            }
        });
    });
});


let reviews = [];
let currentRating = 0;

function createStarRating(rating) {
    const stars = [];
    for (let i = 1; i <= 5; i++) {
        stars.push(`<i class="ri-star-${i <= rating ? 'fill' : 'line'} text-${i <= rating ? 'yellow-400' : 'gray-300'}"></i>`);
    }
    return stars.join('');
}

function updateReviewSummary() {
    const reviewCount = reviews.length;
    const averageRating = reviewCount ? reviews.reduce((sum, r) => sum + r.rating, 0) / reviewCount : 0;

    document.getElementById('reviewCount').innerText = `${reviewCount} Review(s)`;
    document.getElementById('averageRatingStars').innerHTML = createStarRating(averageRating);
    document.getElementById('averageRatingText').innerText = `${averageRating.toFixed(1)} out of 5`;
}

function displayReviews() {
    const reviewList = document.getElementById('reviewList');
    reviewList.innerHTML = reviews.map(review => `
        <div class="flex space-x-4 pb-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
            <div class="flex-shrink-0">
                <img src="${review.avatar}" alt="${review.author}" class="w-12 h-12 rounded-full">
            </div>
            <div class="flex-grow">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">${review.author}</h4>
                    <span class="text-sm text-gray-500 dark:text-gray-400">${review.date}</span>
                </div>
                <div class="flex mb-2">
                    ${createStarRating(review.rating)}
                </div>
                <p class="text-gray-600 dark:text-gray-300">${review.content}</p>
            </div>
        </div>
    `).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    const starContainer = document.getElementById('starRating');
    const reviewForm = document.getElementById('reviewForm');

    // Initialize stars for rating
    starContainer.addEventListener('click', (event) => {
        if (event.target.tagName === 'I') {
            const stars = Array.from(starContainer.children);
            currentRating = stars.indexOf(event.target) + 1;
            stars.forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < currentRating);
                star.classList.toggle('text-gray-300', index >= currentRating);
            });
        }
    });

    // Handle form submission
    reviewForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const reviewContent = document.getElementById('reviewContent').value;
        const avatar = 'https://via.placeholder.com/150'; // Placeholder avatar
        const author = 'Anonymous'; // Placeholder author
        const date = new Date().toLocaleDateString();

        reviews.push({
            author,
            avatar,
            rating: currentRating,
            date,
            content: reviewContent
        });
        displayReviews();
        updateReviewSummary();

        reviewForm.reset();
        currentRating = 0;
        starContainer.querySelectorAll('i').forEach(star => star.className = 'ri-star-fill text-2xl text-gray-300 cursor-pointer');
    });

    updateReviewSummary();
    displayReviews();
});
