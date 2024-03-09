/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.appUrl = document.head.querySelector('meta[name="base-url"]').content;
window.key = document.head.querySelector('meta[name="app-key"]').content;
window.clientId = document.head.querySelector('meta[name="client-id"]').content;
window.clientSecret = document.head.querySelector(
    'meta[name="client-secret"]'
).content;

import axios from "axios";

window.axiosInstance = axios.create({
    baseURL:
        document.head.querySelector('meta[name="base-url"]').content + "/api/",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        Authorization: `Bearer ${localStorage.getItem("TOKEN")}`,
    },
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
