const CACHE_NAME = 'skyflow-v1';
const OFFLINE_ASSETS = [
    '/favicon.svg',
    '/icon-192.png',
    '/icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(OFFLINE_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// Estratégia "network first" simples: tenta a rede, cai pro cache se offline.
// Não faz cache de páginas autenticadas/dinâmicas (GET simples de assets estáticos apenas).
self.addEventListener('fetch', (event) => {
    if (!event.request.url.startsWith('http')) {
        return; 
    }
    if (event.request.method !== 'GET') return;

    const url = new URL(event.request.url);
    const isStaticAsset = OFFLINE_ASSETS.includes(url.pathname) || url.pathname.startsWith('/build/');

    if (!isStaticAsset) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                const clone = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                return response;
            })
            .catch(() => caches.match(event.request))
    );
});
