const CACHE_NAME = 'time2play-admin-v1';
const urlsToCache = [
    '/',
    '/login',
    '/assets/plugins/fontawesome-free/css/all.min.css',
    '/assets/plugins/bootstrap/css/bootstrap.min.css',
    '/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
    '/assets/css/adminlte.min.css',
    '/assets/plugins/jquery/jquery.min.js',
    '/assets/plugins/bootstrap/js/bootstrap.bundle.min.js',
    '/assets/js/adminlte.min.js'
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            }
        )
    );
});
