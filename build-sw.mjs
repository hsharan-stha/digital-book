// build-sw.mjs
import { generateSW } from 'workbox-build';

await generateSW({
  swDest: 'public/service-worker.js',
  globDirectory: 'public',
  globPatterns: [
    'build/**/*.*',  // your Vite/Mix output (adjust path)
    'css/**/*.*',
    'js/**/*.*',
    'favicon.ico',
    'manifest.webmanifest'
  ],
  skipWaiting: true,
  clientsClaim: true,
  offlineGoogleAnalytics: true,
  runtimeCaching: [
    // 1) HTML navigations => offline-first shell with fallback
    {
      urlPattern: ({ request, url }) => {
        return request.mode === 'navigate';
      },
      handler: 'NetworkFirst',
      options: {
        cacheName: 'pages',
        networkTimeoutSeconds: 3,
        plugins: [{
          // Fallback to /offline when both network & cache miss
          cacheWillUpdate: async ({ response }) =>
            response && response.status === 200 ? response : null
        }]
      }
    },
    // 2) Static CDN assets
    {
      urlPattern: ({ url }) => /\.(?:css|js|woff2|png|jpg|svg)$/.test(url.pathname),
      handler: 'StaleWhileRevalidate',
      options: { cacheName: 'assets' }
    },
    // 3) GET API responses (cache + revalidate)
    {
      urlPattern: /\/api\/.*$/i,
      handler: 'NetworkFirst',
      options: {
        cacheName: 'api-get',
        plugins: [
          { cacheWillUpdate: async ({ response }) =>
              response && response.status === 200 ? response : null }
        ]
      }
    }
  ]
});
