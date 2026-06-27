import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Pusher = Pusher;

function asInt(value, fallback) {
    const parsed = Number.parseInt(String(value ?? ''), 10);

    return Number.isFinite(parsed) ? parsed : fallback;
}

function isHttpsScheme(value) {
    return String(value || '').toLowerCase() === 'https';
}

window.createPortalEcho = function createPortalEcho(runtime = {}) {
    const broadcaster = runtime.broadcaster || import.meta.env.VITE_BROADCAST_CONNECTION || 'reverb';

    if (broadcaster === 'null' || broadcaster === 'log') {
        return null;
    }

    if (broadcaster === 'pusher') {
        return new Echo({
            broadcaster: 'pusher',
            key: runtime.key || import.meta.env.VITE_PUSHER_APP_KEY || '',
            cluster: runtime.cluster || import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
            wsHost: runtime.host || import.meta.env.VITE_PUSHER_HOST || undefined,
            wsPort: asInt(runtime.port || import.meta.env.VITE_PUSHER_PORT, 80),
            wssPort: asInt(runtime.port || import.meta.env.VITE_PUSHER_PORT, 443),
            forceTLS: isHttpsScheme(runtime.scheme || import.meta.env.VITE_PUSHER_SCHEME || 'https'),
            enabledTransports: ['ws', 'wss'],
            authEndpoint: runtime.authEndpoint || '/broadcasting/auth',
            csrfToken: runtime.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        });
    }

    return new Echo({
        broadcaster: 'reverb',
        key: runtime.key || import.meta.env.VITE_REVERB_APP_KEY || '',
        wsHost: runtime.host || import.meta.env.VITE_REVERB_HOST || window.location.hostname,
        wsPort: asInt(runtime.port || import.meta.env.VITE_REVERB_PORT, 80),
        wssPort: asInt(runtime.port || import.meta.env.VITE_REVERB_PORT, 443),
        forceTLS: isHttpsScheme(runtime.scheme || import.meta.env.VITE_REVERB_SCHEME || 'https'),
        enabledTransports: ['ws', 'wss'],
        authEndpoint: runtime.authEndpoint || '/broadcasting/auth',
        csrfToken: runtime.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
    });
};
