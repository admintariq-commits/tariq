# TARIQ Offline Assets Fix

This package removes runtime CDN dependencies from the Laravel Blade pages and bundles Tailwind CSS, Alpine.js, Chart.js, Font Awesome, and Leaflet through Vite and npm. The Dockerfile includes a Node build stage so Render creates `public/build` during deployment.

## Windows installation

From the local TARIQ project directory, copy the package files while preserving their folders. The safest method is to extract this ZIP directly into the project folder and choose **Replace** for existing files.

After extraction, run:

```powershell
npm install
npm run build
git diff --check
git status
```

The build must complete without errors. Do not delete `resources/css/app.css`, `resources/js/app.js`, `vite.config.js`, `package.json`, or `package-lock.json`.

## Verification

The Blade files should not contain `cdn.tailwindcss.com`, `cdn.jsdelivr.net`, `cdnjs.cloudflare.com`, `fonts.googleapis.com`, or `unpkg.com`. The Dockerfile must contain a `node:22-alpine AS frontend` stage and copy `/app/public/build` into `/var/www/html/public/build`.

## Commit and push

Only after `npm run build` succeeds:

```powershell
git add Dockerfile package.json package-lock.json resources/js/app.js resources/views
git commit -m "Bundle frontend assets locally for offline-safe styling"
git push origin main
```
