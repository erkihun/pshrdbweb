# Deployment Guide (Plesk)

This project is a Laravel 12 app configured for Plesk-friendly deployment.

## Production .env Checklist

Set these in `.env` on the server:

- `APP_ENV=production`
- `APP_DEBUG=false`
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
