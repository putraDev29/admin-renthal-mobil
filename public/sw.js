const CACHE_NAME = 'amiitrans-pwa-v3';
const CORE_ASSETS = [
    '/manifest.json',
    '/offline.html',
    '/assets/images/logo.png',
    '/assets/images/icon-96.png',
    '/assets/images/icon-192.png',
    '/assets/images/icon-maskable-192.png',
    '/assets/images/icon-512.png',
    '/assets/images/icon-maskable-512.png',
    '/assets/images/apple-touch-icon.png',
    '/assets/images/screenshot-wide.png',
    '/assets/images/screenshot-narrow.png'
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return Promise.allSettled(
                CORE_ASSETS.map((url) => {
                    return fetch(url, { cache: 'no-cache' })
                        .then((res) => {
                            if (res && res.status === 200) {
                                return cache.put(url, res);
                            }
                        })
                        .catch((err) => {
                            console.warn('PWA: could not pre-cache', url, err);
                        });
                })
            );
        }).then(() => self.skipWaiting())
    );
});

// Activate Event - Clean old caches & claim clients immediately
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip Chrome extension or non-http requests
    if (!request.url.startsWith('http')) {
        return;
    }

    // HTML Navigation requests: Network-First with Offline Fallback
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    return response;
                })
                .catch(async () => {
                    const cachedOffline = await caches.match('/offline.html');
                    if (cachedOffline) {
                        return cachedOffline;
                    }
                    return new Response(
                        '<h1>Anda sedang offline</h1><p>Silakan hubungkan kembali perangkat ke internet.</p>',
                        { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                    );
                })
        );
        return;
    }

    // Static assets (CSS, JS, Fonts, Images): Cache-First / Network Fallback
    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
                // Background update cache (stale-while-revalidate)
                fetch(request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200 && networkResponse.type !== 'opaque') {
                            caches.open(CACHE_NAME).then((cache) => cache.put(request, networkResponse));
                        }
                    })
                    .catch(() => {});
                return cachedResponse;
            }

            return fetch(request)
                .then((response) => {
                    if (!response || response.status !== 200 || response.type === 'opaque') {
                        return response;
                    }
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(() => {
                    if (request.destination === 'image') {
                        return caches.match('/assets/images/icon-192.png');
                    }
                });
        })
    );
});
