const CACHE_NAME = 'hardball-smokehouse-v1';
const STATIC_ASSETS = [
    '/',
    '/menu',
    '/order/checkout',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

const MENU_CACHE_NAME = 'hardball-menu-v1';

// Install event - cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME && name !== MENU_CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // API requests - network first, cache fallback
    if (url.pathname.startsWith('/api/')) {
        // Menu API - cache aggressively
        if (url.pathname.includes('/api/menu') || url.pathname.includes('/api/menu-categories')) {
            event.respondWith(handleMenuCache(request));
            return;
        }

        // Other API - network only
        event.respondWith(fetch(request));
        return;
    }

    // Static assets - cache first
    if (
        request.destination === 'image' ||
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'font'
    ) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // HTML pages - stale-while-revalidate
    event.respondWith(staleWhileRevalidate(request));
});

// Cache first strategy
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;

    const response = await fetch(request);
    const cache = await caches.open(CACHE_NAME);
    cache.put(request, response.clone());
    return response;
}

// Stale while revalidate strategy
async function staleWhileRevalidate(request) {
    const cached = await caches.match(request);

    const fetchPromise = fetch(request).then((response) => {
        if (response.ok) {
            const cache = caches.open(CACHE_NAME);
            cache.then((c) => c.put(request, response.clone()));
        }
        return response;
    }).catch(() => cached);

    return cached || fetchPromise;
}

// Menu-specific caching strategy
async function handleMenuCache(request) {
    const cache = await caches.open(MENU_CACHE_NAME);
    const cached = await cache.match(request);

    // Return cached immediately if available
    if (cached) {
        // Update cache in background
        fetch(request).then((response) => {
            if (response.ok) {
                cache.put(request, response);
            }
        }).catch(() => {});

        return cached;
    }

    // No cache - fetch and store
    try {
        const response = await fetch(request);
        if (response.ok) {
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        // Return offline fallback for menu
        return new Response(JSON.stringify({
            error: 'Offline',
            message: 'Menu data not available offline'
        }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Background sync for orders
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-orders') {
        event.waitUntil(syncPendingOrders());
    }
});

async function syncPendingOrders() {
    // Sync any pending offline orders
    const pendingOrders = await getPendingOrders();
    for (const order of pendingOrders) {
        try {
            await fetch('/api/orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(order),
            });
            await removePendingOrder(order.id);
        } catch (error) {
            console.error('Failed to sync order:', error);
        }
    }
}

// Placeholder functions for pending order management
async function getPendingOrders() {
    // Implementation would use IndexedDB
    return [];
}

async function removePendingOrder(id) {
    // Implementation would use IndexedDB
}

// Push notifications for order updates
self.addEventListener('push', (event) => {
    const data = event.data?.json() || {};

    event.waitUntil(
        self.registration.showNotification(data.title || 'Hardball Smokehouse', {
            body: data.body || 'Your order has been updated',
            icon: '/icons/icon-192x192.png',
            badge: '/icons/icon-72x72.png',
            tag: data.orderId || 'order-update',
            requireInteraction: false,
            data: data,
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    event.waitUntil(
        clients.matchAll({ type: 'window' }).then((clientList) => {
            const url = event.notification.data?.url || '/';

            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
