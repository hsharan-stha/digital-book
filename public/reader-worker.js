// sw.js — resilient, scope-aware App Shell caching (images handled in-page via IndexedDB)

const VERSION = 'v4';
const CACHE = `reader-shell-${VERSION}`;

// Resolve relative to SW scope so it works at "/" or under a subfolder.
const ROOT = new URL('./', self.registration.scope);

const SHELL_PATHS = [
  '',                 // index under the scope (optional if your index is accessible)
  'manifest.json',
  'reader-worker.js',
  'css/style.css',
  'css/book/jquery.ui.css',
  'css/book/jquery.ui.html4.css',
  'js/jquery/jquery.min.1.7.js',
  'js/jquery/jquery-ui-1.8.20.custom.min.js',
  'js/jquery/jquery.ui.touch-punch.min.js',
  'js/lib/paltau.min.js',
  'js/lib/tesseract.min.js',
  // add fonts/logos as needed
];

const SHELL_URLS = SHELL_PATHS.map(p => new URL(p, ROOT).href);
const SHELL_SET  = new Set(SHELL_URLS);

// Install: cache each item, skipping failures (avoids breaking install)
self.addEventListener('install', (evt) => {
  evt.waitUntil((async () => {
    const cache = await caches.open(CACHE);
    for (const url of SHELL_URLS) {
      try {
        const res = await fetch(url, { cache: 'no-cache' });
        if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);
        await cache.put(url, res.clone());
      } catch (err) {
        console.warn('[SW] Skipped (not cached):', url, err);
      }
    }
  })());
  self.skipWaiting();
});

// Activate: clean old versions
self.addEventListener('activate', (evt) => {
  evt.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k.startsWith('reader-shell-') && k !== CACHE).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Fetch
self.addEventListener('fetch', (evt) => {
  const req = evt.request;
  const isNav =
    req.mode === 'navigate' ||
    req.destination === 'document' ||
    (req.headers.get('accept') || '').includes('text/html');

  if (isNav) {
    // Cache-first navigations with fallback to scope index
    evt.respondWith(
      caches.match(req).then(hit => hit || fetch(req).then(r => {
        const copy = r.clone();
        caches.open(CACHE).then(c => c.put(req, copy));
        return r;
      }).catch(() => caches.match(new URL('', ROOT).href)))
    );
    return;
  }

  if (SHELL_SET.has(req.url)) {
    // Cache-first for shell, with background refresh
    evt.respondWith(
      caches.match(req).then(hit => {
        const net = fetch(req).then(r => {
          if (r.ok || r.type === 'opaque') {
            const copy = r.clone();
            caches.open(CACHE).then(c => c.put(req, copy));
          }
          return r;
        }).catch(() => hit);
        return hit || net;
      })
    );
    return;
  }

  // Everything else: pass-through (page images handled via IndexedDB)
});

// Optional immediate activation hook
self.addEventListener('message', (evt) => {
  if (evt.data === 'SKIP_WAITING') self.skipWaiting();
});
