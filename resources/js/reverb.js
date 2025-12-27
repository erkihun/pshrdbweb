import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

export function configureEcho(config = {}) {
    if (window.Echo) {
        return window.Echo;
    }

    const defaultConfig = {
        broadcaster: 'pusher',
        key: import.meta.env.VITE_REVERB_APP_KEY ?? import.meta.env.VITE_PUSHER_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
        wsPort: Number(import.meta.env.VITE_REVERB_PORT ?? (window.location.port || (window.location.protocol === 'https:' ? 443 : 80))),
        wssPort: Number(import.meta.env.VITE_REVERB_PORT ?? (window.location.port || (window.location.protocol === 'https:' ? 443 : 80))),
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        encrypted: true,
        disableStats: true,
        enabledTransports: ['http-streaming'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
            },
        },
        cluster: import.meta.env.VITE_REVERB_APP_CLUSTER ?? import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        ...config,
    };

    window.Echo = new Echo(defaultConfig);

    return window.Echo;
}
