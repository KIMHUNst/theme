self.addEventListener('install', function (event) {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.open('cab-cache-v1').then(function (cache) {
            return cache.match(event.request).then(function (response) {
                const fetchPromise = fetch(event.request)
                    .then(function (networkResponse) {
                        if (event.request.method === 'GET') {
                            cache.put(event.request, networkResponse.clone());
                        }
                        return networkResponse;
                    })
                    .catch(function () {
                        return response;
                    });

                return response || fetchPromise;
            });
        })
    );
});
