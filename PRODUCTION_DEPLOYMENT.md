# Production Deployment Guide

## Deployment on Render (Recommended)

1. **Environment Variables**: In your Render Web Service dashboard, go to **Environment** and add:
   - `DATABASE_URL`: Weka Internal URL yako. (Ukishaweka hii, huna haja ya kuweka DB_HOST, DB_PORT, au DB_DATABASE pembeni).
   - `APP_KEY`: Your generated Laravel key.
   - `NODE_VERSION`: `20` (or your preferred version).
2. **Build Command**: `composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && npm install && npm run build`
3. **Start Command**: `php artisan migrate --force && apache2-foreground` (or your specific start command).

## Updating the App

Whenever you make changes to the code:
1. `git add .`
2. `git commit -m "Your update message"`
3. `git push origin main`
Render will automatically detect the push and start a new deployment.

### How to verify deployment on Render:
1. Go to the **Dashboard** and select your Web Service.
2. Check the **Status Badge**: If it says **Live** (green), the deployment is finished.
3. Check **Events**: Look for "Deploy live for v...".
4. Check **Logs**: Ensure there are no errors in the startup logs.
5. Open your **App URL** to see the changes live.

## 1. Clone or update the repo on production server

If the project is not yet on the server:

```bash
git clone https://github.com/charmprince459-cmd/Tariq.git /path/to/project
cd /path/to/project
```

If the repo already exists on production:

```bash
cd /path/to/project
git pull origin main
```

## 2. Create the production environment file

Copy the example file and edit production values:

```bash
cp .env.production.example .env
```

Update these values in `.env`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-production-domain.com`
- `APP_KEY` (generated next)
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`

## 3. Install PHP dependencies

```bash
composer install --optimize-autoloader --no-dev
```

## 4. Generate application key

```bash
php artisan key:generate --force
```

## 5. Run database migrations

```bash
php artisan migrate --force
```

## 6. Cache config/routes/views

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 7. Build frontend assets

```bash
npm install
npm run build
```

## 8. Set file permissions

For Linux production, ensure storage and bootstrap/cache are writable:

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 9. Restart web server

Restart your web server or PHP-FPM so the new code and environment load.

## Notes

- Do NOT commit `.env` or `.env.production` to Git.
- Keep `.env.production.example` in the repo as a safe template.
- If you need, I can also help you fill the `.env` values for your production server.
