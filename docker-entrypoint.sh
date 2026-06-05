#!/bin/sh
set -e

# Ensure the default app key exists and the SQLite database file exists if used.
if [ ! -f .env ]; then
  cp .env.example .env
fi

mkdir -p database
if [ "$(grep -E '^DB_CONNECTION=' .env | cut -d'=' -f2)" = "sqlite" ]; then
  touch database/database.sqlite
  chmod 777 database/database.sqlite
fi

php artisan key:generate --ansi --force

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
