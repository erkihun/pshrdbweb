# Production Deployment Checklist

## Required Server Extensions
- PHP 8.1+ with `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `zip`
- `pdo_mysql` for MySQL connections
- `gd` or `imagick` if image hashing or resizing occurs
- `opcache` enabled for runtime speed

## Deployment Tasks
1. `composer install --no-dev --optimize-autoloader`
2. `php artisan optimize:clear`
3. `php artisan config:cache`
4. `php artisan route:cache`
5. `php artisan view:cache`
6. `php artisan event:cache`
7. `npm ci`
8. `npm run build`

## Post-Deployment Setup
- `php artisan storage:link`
- Ensure `storage/` and `bootstrap/cache/` are writable (`chmod -R 775 storage bootstrap/cache`)
- Restart queue workers and schedulers if active
- Monitor `storage/logs/laravel.log` for errors after deployment

## Asset delivery & compression
- The templates rely on [`@vite`] bundles. Run `npm run build` and verify the `public/build` manifest is synced with the deployed files.
- Enable gzip and/or brotli compression on the web server (Nginx `gzip on; gzip_static on; brotli on;`) so compiled CSS/JS and SVGs download faster even on mobile.

## Config caching notes
- Re-run `php artisan config:cache` whenever `.env` or any config file changes to keep cached settings synchronized with the runtime.
- Always `php artisan config:clear` before editing config files locally or when troubleshooting stale configuration.
