# Deploying to Hostinger

Target layout on shared hosting (keeps `.env`, `vendor/` and source code out of the web root):

```
~/domains/<domain>/
├── laravel/          ← git clone of this repository (private)
└── public_html  →    symlink to laravel/public (the ONLY web-accessible folder)
```

## Live setup (discovertimes.com)

- App: `~/homedecor` (git clone), web root: `~/domains/discovertimes.com/public_html -> ~/homedecor/public`.
- WordPress backup (files + DB): `~/backups/`.
- Update: `cd ~/homedecor && bash deploy/deploy.sh`.

## 1. Prerequisites (hPanel)

1. **Advanced → SSH Access → Enable** (note host, port, username).
2. **Advanced → PHP Configuration**: PHP **8.3** (8.2 minimum). Extensions: `pdo_mysql`, `gd` (with WebP), `fileinfo`, `mbstring`, `exif`, `intl`.
3. **Databases → MySQL**: create database + user (done: `u793352147_homedecor`).
4. **Security → SSL**: enable the free SSL certificate.
5. **Emails**: create a mailbox (e.g. `hello@<domain>`) for form notifications.

## 2. First deployment

```bash
ssh -p <port> <user>@<host>
cd ~/domains/<domain>

# Back up whatever is currently live (e.g. WordPress) before removing it
mkdir -p ~/backups && tar -czf ~/backups/public_html-$(date +%F).tar.gz public_html

git clone https://github.com/<owner>/homedecor.git laravel
cd laravel
composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts
php artisan package:discover          # Hostinger blocks proc_open for Composer scripts
cp .env.example .env && nano .env      # APP_URL=https://<domain>, DB_*, MAIL_*, ADMIN_*
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force             # content + first admin (from ADMIN_EMAIL / ADMIN_PASSWORD)
# then remove ADMIN_PASSWORD from .env

cd .. && rm -rf public_html && ln -s laravel/public public_html
cd laravel && php artisan optimize
chmod -R ug+rwX storage bootstrap/cache public/media
```

> If your plan does not allow replacing `public_html` with a symlink, copy `laravel/public/*` into `public_html/`
> and change the two paths in `public_html/index.php` to `__DIR__.'/../laravel/vendor/autoload.php'` and
> `__DIR__.'/../laravel/bootstrap/app.php'`. Never upload the project root into `public_html`.

## 3. Updates

```bash
cd ~/domains/<domain>/laravel && bash deploy/deploy.sh
```

Frontend assets (`public/build`) and the optimised image library (`public/media/library`) are committed, so the
server needs no Node.js. After changing CSS/JS locally: `npm run build`, commit, push, deploy.

## 4. Scheduler / queues

Mail is sent synchronously (`QUEUE_CONNECTION=sync`), so no queue worker is needed. Nothing requires cron today; if
scheduled tasks are added later, add this cron job in hPanel: `* * * * * cd ~/domains/<domain>/laravel && php artisan schedule:run`.

## 5. Backups & restore

- **Database**: hPanel → Databases → phpMyAdmin → Export, or
  `mysqldump -u <user> -p <db> | gzip > ~/backups/db-$(date +%F).sql.gz`.
- **Uploads**: `tar -czf ~/backups/uploads-$(date +%F).tar.gz laravel/public/media/uploads laravel/storage/app/private`.
- Hostinger also keeps automatic backups (hPanel → Files → Backups).
- **Restore**: `gunzip < db-backup.sql.gz | mysql -u <user> -p <db>`; extract the uploads archive in `~/domains/<domain>/`.

## 6. Post-launch checklist

- `APP_ENV=production`, `APP_DEBUG=false`, HTTPS URL in `APP_URL`.
- Admin dashboard → Launch checklist is all green (business details, legal pages reviewed, etc.).
- Submit `https://<domain>/sitemap.xml` in Google Search Console; add the verification code in Settings.
- Test the contact and consultation forms and confirm notification emails arrive.
