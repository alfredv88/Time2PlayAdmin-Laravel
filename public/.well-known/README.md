# Time2Play Admin - Firebase Hosting

## Deployment Status
✅ Ready for Firebase Hosting

## Files Structure
- `index.php` - Laravel entry point
- `index.html` - Redirect to Laravel
- `404.html` - Error page
- `robots.txt` - SEO configuration
- `sitemap.xml` - Site map
- `manifest.json` - PWA manifest
- `sw.js` - Service Worker
- `offline.html` - Offline page
- `health.html` - Health check
- `assets/` - Static assets
- `build/` - Compiled assets

## Firebase Configuration
- Project: time2play-ed370
- URL: https://time2play-ed370.web.app
- Environment: Production

## Deployment Commands
```bash
firebase login
firebase use time2play-ed370
firebase deploy --only hosting
```

## Health Check
Visit: https://time2play-ed370.web.app/health.html
