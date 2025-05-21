import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    forceTLS: true
});

const userId = document.querySelector('meta[name="user-id"]').content;
if (userId) {
    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            // Ajouter la nouvelle notification en haut de la liste
            addNewNotification(notification);
            // Mettre à jour le compteur
            incrementNotificationCount();
        });
}

function addNewNotification(notification) {
    const notificationsList = document.querySelector('.notifications-list');
    if (notificationsList) {
        const notificationHtml = `
            <a href="${notification.action_url}" 
               class="notification-link block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 unread"
               data-notification-id="${notification.id}">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${getNotificationIcon(notification.type)}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                        <p class="text-xs text-gray-500">${notification.message}</p>
                        <p class="text-xs text-gray-400">Just now</p>
                    </div>
                </div>
            </a>
        `;
        notificationsList.insertAdjacentHTML('afterbegin', notificationHtml);
    }
}

function getNotificationIcon(type) {
    // Retourne l'icône appropriée selon le type
    // Implémentez selon vos besoins
}