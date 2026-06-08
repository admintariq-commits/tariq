#!/bin/sh
set -e

# Create .env from .env.example if it doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# If Render provides a database URL (DATABASE_URL or DB_URL), force PostgreSQL
# settings in the generated .env so runtime doesn't pick up old MySQL values.
if [ -n "$DATABASE_URL" ] || [ -n "$DB_URL" ]; then
  echo "Render database URL detected - enforcing PostgreSQL settings"
  # Ensure DB_CONNECTION is pgsql
  if grep -q "^DB_CONNECTION=" .env; then
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
  else
    echo "DB_CONNECTION=pgsql" >> .env
  fi

  # Force DB_PORT to 5432 (PostgreSQL). This overrides any leftover 3306 value.
  if grep -q "^DB_PORT=" .env; then
    sed -i 's/^DB_PORT=.*/DB_PORT=5432/' .env
  else
    echo "DB_PORT=5432" >> .env
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
