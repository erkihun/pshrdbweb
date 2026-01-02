# Deployment Guide (Plesk)

This project is a Laravel 12 app configured for Plesk-friendly deployment.

## Production .env Checklist

Set these in `.env` on the server:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `LOG_LEVEL=warning`
- `APP_URL=https://your-domain.tld`
- `APP_KEY=base64:...` (generate if missing)
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_PORT=3306`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`
- `CACHE_DRIVER=file` (or `redis`)
- `QUEUE_CONNECTION=database` (or `redis`)
- `SESSION_DRIVER=database`
- `SESSION_SECURE_COOKIE=true`
- `SESSION_SAME_SITE=lax`
- `MAIL_MAILER=smtp`
- `MAIL_HOST=...`
- `MAIL_PORT=...`
- `MAIL_USERNAME=...`
- `MAIL_PASSWORD=...`
- `MAIL_ENCRYPTION=tls`
- `MAIL_FROM_ADDRESS=...`
- `MAIL_FROM_NAME=...`

## Plesk Steps

1. Point the domain document root to `/public`.
2. Upload the project (or pull from Git).

Plesk's Git deployment tasks run from the document root (usually `/public`), so `php artisan` commands need an entry in that directory. This repo includes a small stub at `public/artisan` that simply requires the real root `artisan` file, and the `public/.htaccess` rules block HTTP access to the stub.
3. Run Composer:

```bash
composer install --no-dev --optimize-autoloader
```

4. Install frontend assets:

```bash
npm ci
npm run build
```

5. Set permissions (Plesk example):

```bash
chmod -R 775 storage bootstrap/cache
```

6. Create the storage symlink:

```bash
php artisan storage:link
```

7. Run migrations and caches:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Production cache workflow

Wrap releases in the following commands so cached configuration, routes, and views are refreshed:

```bash
php artisan down --secret="maintenance-access"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
php artisan up
```

Include the same sequence in automated deployments or release playbooks so the application boots with the cached files every time.

## PHP tuning for production

Update `php.ini` (or the equivalent pool configuration) with these recommended values:

```
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
realpath_cache_size=4096K
realpath_cache_ttl=600
```

An OPCache-aware deployment reduces the first-request penalty and the realpath cache improves autoload lookup time for large applications.

## Asset build

Ensure the production asset bundle is created from the `/public` document root:

```bash
npm ci
npm run build
```

Double-check that every Blade template uses `@vite()` for styles/scripts and that no pages load the development server assets (`http://localhost:5173`). The build already copies TinyMCE to `public/vendor/tinymce`, and the admin layouts now load it only when a rich-text editor is present.

## Image delivery & helper

- Set `Last-Modified`/`Cache-Control` headers via your web server for `/storage`, `/css`, `/js`, and `/images`.
- Convert large JPEG/PNG assets to WEBP before uploading, and keep a consistent max dimension (e.g. 1200 px) to prevent oversized downloads.
- All `<img>` tags should include `loading="lazy"` (except hero visuals), plus either `width`/`height` or an explicit aspect ratio, to avoid layout shifts.
- For new templates use the `x-optimized-image` helper component, which adds `object-cover`, `max-height`, `lazy` loading, and optional WEBP sources.

## HTTP caching for public pages

The routing bootstrap now provides a `public.cache` middleware that sets `Cache-Control: public, max-age=300, s-maxage=600` and an `ETag` header for these non-authenticated GET endpoints:

- `/` (home)
- `/about`
- `/staff`
- `/citizen-charter`
- `/organization/contact`

Attach `->middleware('public.cache')` to those routes so browsers and CDNs can keep them hot for 5 minutes without serving stale private content.

## Final checks

Before pushing or deploying:

```bash
php artisan route:list
php artisan config:cache
php artisan optimize
```

Verify there are no new errors in `storage/logs/laravel.log`, confirm the maintenance secret is stored somewhere safe, and fully test the pages listed above to ensure they render correctly with the new headers and cached assets.

## Queue + Scheduler

- Queue worker (for notifications, email, etc):

```bash
php artisan queue:work --tries=3 --timeout=90
```

- Scheduler (audit log cleanup):

Add a cron job in Plesk (or server crontab):

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Security Hardening Notes

- HTTPS is forced in production.
- Secure session cookies are enabled by default in production.
- Content Security Policy headers are set in production.
- A `contact` rate limiter is defined; apply it to the contact form route with `throttle:contact`.

## Useful Artisan Commands (Production)

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```
