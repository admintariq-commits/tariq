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

# Render assigns a dynamic port via $PORT. Make Apache listen on it.
# Falls back to 80 for local Docker runs.
APACHE_LISTEN_PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${APACHE_LISTEN_PORT}"
sed -ri "s!Listen 80!Listen ${APACHE_LISTEN_PORT}!g" /etc/apache2/ports.conf
sed -ri "s!<VirtualHost \*:80>!<VirtualHost *:${APACHE_LISTEN_PORT}>!g" /etc/apache2/sites-available/000-default.conf

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
