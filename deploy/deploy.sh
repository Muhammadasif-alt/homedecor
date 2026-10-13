#!/usr/bin/env bash
# Server-side deploy for Hostinger shared hosting. Run from the Laravel root over SSH:
#   bash deploy/deploy.sh
# Layout: ~/domains/<domain>/laravel  (this app, private)
#         ~/domains/<domain>/public_html -> laravel/public  (symlink = the only web root)
set -euo pipefail

cd "$(dirname "$0")/.."
PHP="${PHP_BIN:-php}"
COMPOSER="${COMPOSER_BIN:-composer}"

echo "→ Pulling latest code"
git pull --ff-only origin main

echo "→ Installing PHP dependencies"
$COMPOSER install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "→ Maintenance mode"
$PHP artisan down --retry=30 || true

echo "→ Migrating database"
$PHP artisan migrate --force

echo "→ Caching config, routes, views and events"
$PHP artisan optimize:clear
$PHP artisan optimize

chmod -R ug+rwX storage bootstrap/cache public/media

$PHP artisan up
echo "✓ Deployed $(git rev-parse --short HEAD)"
