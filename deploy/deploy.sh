#!/usr/bin/env bash
# Server-side deploy for Hostinger shared hosting. Run from the Laravel root over SSH:
#   bash deploy/deploy.sh
# Layout: ~/homedecor                              (this app, outside the web root)
#         ~/domains/discovertimes.com/public_html -> ~/homedecor/public  (symlink = the only web root)
set -euo pipefail

cd "$(dirname "$0")/.."
PHP="${PHP_BIN:-php}"
COMPOSER="${COMPOSER_BIN:-composer}"

echo "→ Pulling latest code"
git pull --ff-only origin main

echo "→ Installing PHP dependencies"
# --no-scripts: Hostinger disables proc_open for Composer scripts, so run package discovery directly.
$COMPOSER install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts
$PHP artisan package:discover

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
