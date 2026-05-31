#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

echo "=== Tariq production deployment helper ==="

if [ ! -d .git ]; then
  echo "Error: This directory is not a Git repository."
  exit 1
fi

if [ ! -f .env ]; then
  echo ".env file not found. Copying .env.production.example to .env"
  cp .env.production.example .env
  echo "Please edit .env with your production values before continuing."
  exit 0
fi

echo "Updating repo from origin/main..."
git fetch origin main

git reset --hard origin/main

echo "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

echo "Generating app key if missing..."
if ! grep -q '^APP_KEY=.*[^=]$' .env; then
  php artisan key:generate --force
fi

echo "Running migrations..."
php artisan migrate --force

echo "Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Building frontend assets..."
npm install
npm run build

echo "Deployment helper finished. Verify your web server configuration and restart PHP/FPM if needed."
