#!/bin/sh
set -e

# Create .env from .env.example if it doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# If DATABASE_URL is set in environment (from Render), ensure DB_CONNECTION is set to pgsql
if [ -n "$DATABASE_URL" ]; then
  echo "DATABASE_URL detected - using PostgreSQL connection"
  # Make sure DB_CONNECTION is set to pgsql to match the DATABASE_URL
  if ! grep -q "^DB_CONNECTION=pgsql" .env; then
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
  fi
fi

mkdir -p database
if [ "$(grep -E '^DB_CONNECTION=' .env | cut -d'=' -f2)" = "sqlite" ]; then
  touch database/database.sqlite
  chmod 777 database/database.sqlite
fi

php artisan key:generate --ansi --force
php artisan config:cache

RETRY_COUNT=0
until php artisan migrate --force && php artisan db:seed --force; do
  RETRY_COUNT=$((RETRY_COUNT + 1))
  if [ "$RETRY_COUNT" -ge 6 ]; then
    echo "Failed to run migrations after $RETRY_COUNT attempts. Exiting."
    exit 1
  fi
  echo "Database not ready yet, retrying in 5 seconds... ($RETRY_COUNT/6)"
  sleep 5
done

exec apache2-foreground
