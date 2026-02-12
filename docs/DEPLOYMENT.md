# Hardball Smokehouse – Deployment Guide

## Quick checklist (Forge / typical Laravel deploy)

1. **SSH / server** – Can you reach the server? (`ssh forge@YOUR_SERVER_IP`)
2. **Branch** – Forge should deploy from `development` (or your main branch).
3. **Build** – `npm run build:prod` (or `npm run build`) must run so Vite assets exist; use `build:prod` on low-memory servers to avoid "Killed".
4. **Migrations** – Run `php artisan migrate --force` on deploy.
5. **Env** – `.env` on server has correct `APP_ENV`, `APP_DEBUG=false`, DB, Redis, Pusher, Mail.
6. **Scheduler** – Cron: `* * * * * cd /home/forge/SITE && php artisan schedule:run >> /dev/null 2>&1`
7. **Queue** – Horizon or `queue:work` must be running (Forge “Daemons” or Supervisor).
8. **Cache** – After deploy: `php artisan config:cache` and `php artisan route:cache` (optional).

---

## Forge deploy script (paste into Forge → Site → Deploy Script)

```bash
cd /home/forge/hardball.test  # or your site path from Forge

git pull origin $FORGE_SITE_BRANCH

composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service php8.2-fpm reload ) 9>/tmp/fpmlock

npm ci
npm run build:prod

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan horizon:terminate  # if using Horizon; otherwise restart your queue worker daemon
```

- Replace `hardball.test` with your actual site path (e.g. `hardball.shanebell.com` or whatever Forge shows).
- Replace `php8.2-fpm` with your PHP version (e.g. `php8.3-fpm`).
- Set **Branch** in Forge to `development` (or `main` / whatever you deploy from).

---

## Common deployment issues

| Symptom | Likely cause | Fix |
|--------|----------------|-----|
| 500 after deploy | Missing env, wrong permissions, or no built assets | Check `storage/logs/laravel.log`, ensure `npm run build` ran, `storage`/`bootstrap/cache` writable |
| Blank / white page | Vite manifest or assets missing | Run `npm run build` on server (or in deploy script); check `public/build` exists |
| Scheduler not running | No cron for `schedule:run` | In Forge: Scheduler → add cron above; or SSH and `crontab -e` |
| Queues not processing | Horizon/worker not running | Forge → Daemons: ensure Horizon (or `queue:work`) is enabled and restarted after deploy |
| Migrations fail | DB credentials or version mismatch | Check `.env` DB_*; run `php artisan migrate --force` manually and read error |
| SSH timeout | Firewall or wrong IP | Open port 22 for your IP in cloud firewall; confirm server IP in Forge |
| Event image upload stuck on "Updating..." | Upload too large or timeout | Nginx: `client_max_body_size 10M;` PHP: `upload_max_filesize` and `post_max_size` ≥ 10M. Use image &lt; 5MB or image path instead. After ~55s the page shows a hint to try a smaller image. |
| Deploy fails with "Killed" during build | Server runs out of memory (OOM) during Vite build | Use `npm run build:prod` in deploy script (sets Node heap 4GB). Or add swap on server, or build locally and commit `public/build`. |

---

## Fix: "Permission denied" on deploy (hardballsmokehouse.co.uk)

If deploy fails because `public` is read-only or some files are root-owned (e.g. "cannot create directory at 'public/fonts'", "unable to unlink old 'public/...'"):

**1. SSH in and fix permissions (run once, you’ll be prompted for your sudo password):**

```bash
ssh forge@159.223.188.251
cd /home/forge/hardballsmokehouse.co.uk
sudo chown -R forge:forge public
sudo chmod -R u+w public
```

**2. Set Forge to deploy from `development`**

In Forge → Site → App → **Branch**, set to `development` (not `main`).

**3. Deploy again from Forge** (or run manually):

```bash
cd /home/forge/hardballsmokehouse.co.uk
git fetch origin development
git checkout -B development FETCH_HEAD
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
npm ci
npm run build:prod
php artisan migrate --force
php artisan config:cache
php artisan horizon:terminate   # if you use Horizon
```

In **Forge → Site → App**, set **Branch** to `development` so future "Deploy Now" uses this branch.

---

## What to send if you need more help

- **Where** you deploy (Laravel Forge, VPS manual, shared host, etc.).
- **Exact error**: message from Forge deploy log, or from `storage/logs/laravel.log`, or browser (500 / blank).
- **What you already tried** (e.g. ran migrations, ran `npm run build`).

Then we can target the fix (e.g. adjust deploy script, env, or permissions).
