document.addEventListener('DOMContentLoaded', function() {
    // Marquer une notification comme lue au clic
    document.querySelectorAll('.notification-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const notificationId = this.dataset.notificationId;
            if (notificationId) {
                markAsRead(notificationId);
            }
        });
    });

    // Fonction pour marquer comme lu
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                // Mettre à jour le style visuel
                const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationElement) {
                    notificationElement.classList.remove('unread');
                    updateUnreadCount();
                }
            }
        });
    }

    // Mettre à jour le compteur
    function updateUnreadCount() {
        fetch('/notifications/count')
            .then(response => response.json())
            .then(data => {
                const counter = document.querySelector('.notification-counter');
                if (counter) {
                    counter.textContent = data.count;
                    if (data.count === 0) {
                        counter.style.display = 'none';
                    }
                }
            });
    }
});