#!/bin/sh
set -e

# Create .env from .env.example if it doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Ensure runtime storage directories exist and are writable.
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs database
chown -R www-data:www-data bootstrap/cache storage || true
chmod -R 775 bootstrap/cache storage || true

# Initialize environment variables with safe defaults for Render and local container startup
DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"
DATABASE_URL="${DATABASE_URL:-}"
DB_URL="${DB_URL:-}"
DB_SSLMODE="${DB_SSLMODE:-require}"
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_KEY="${APP_KEY:-}"
export DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD DATABASE_URL DB_URL DB_SSLMODE APP_ENV APP_DEBUG APP_KEY

# If Render provides a database URL (DATABASE_URL or DB_URL), force PostgreSQL
# settings in the generated .env so runtime doesn't pick up old MySQL values.
if [ -n "$DATABASE_URL" ] || [ -n "$DB_URL" ]; then
  echo "Render database URL detected - enforcing PostgreSQL settings"
  DB_URL="${DATABASE_URL:-$DB_URL}"
  export DATABASE_URL="$DB_URL"
  export DB_URL="$DB_URL"

  php -r '
    $url = getenv("DATABASE_URL") ?: getenv("DB_URL");
    if ($url === false) {
      return;
    }
    $parts = parse_url($url);
    if ($parts === false) {
      return;
    }

    $host = $parts["host"] ?? "";
    $query = [];
    if (!empty($parts["query"])) {
      parse_str($parts["query"], $query);
    }

    if (stripos($host, "neon.tech") !== false) {
      if (!isset($query["sslmode"])) {
        $query["sslmode"] = "require";
      }

      $newQuery = http_build_query($query, "", "&", PHP_QUERY_RFC3986);
      $url = ($parts["scheme"] ?? "") . "://";
      if (!empty($parts["user"])) {
        $url .= $parts["user"];
        if (!empty($parts["pass"])) {
          $url .= ":" . $parts["pass"];
        }
        $url .= "@";
      }
      $url .= $host;
      if (!empty($parts["port"])) {
        $url .= ":" . $parts["port"];
      }
      if (!empty($parts["path"])) {
        $url .= $parts["path"];
      }
      if ($newQuery !== "") {
        $url .= "?" . $newQuery;
      }
      printf("DATABASE_URL=%s\n", $url);
      printf("DB_URL=%s\n", $url);
      printf("DB_SSLMODE=require\n");
    }

    $mapping = [
      "host" => "DB_HOST",
      "port" => "DB_PORT",
      "user" => "DB_USERNAME",
      "pass" => "DB_PASSWORD",
    ];
    foreach ($mapping as $key => $env) {
      if (!empty($parts[$key])) {
        printf("%s=%s\n", $env, str_replace("\n", "\\n", $parts[$key]));
      }
    }
    if (!empty($parts["path"])) {
      printf("DB_DATABASE=%s\n", ltrim($parts["path"], "/"));
    }
  ' > /tmp/render_db_env

  if [ -f /tmp/render_db_env ]; then
    . /tmp/render_db_env
    rm -f /tmp/render_db_env
  fi

  echo "DATABASE_URL=$DATABASE_URL"
  echo "DB_URL=$DB_URL"
  echo "DB_HOST=$DB_HOST"
  echo "DB_PORT=$DB_PORT"
  echo "DB_DATABASE=$DB_DATABASE"

  if [ -f .env ]; then
    php -r '
      $envPath = ".env";
      $contents = file($envPath, FILE_IGNORE_NEW_LINES);
      $updates = [
          "DB_CONNECTION" => getenv("DB_CONNECTION") ?: "pgsql",
          "DB_HOST" => getenv("DB_HOST"),
          "DB_PORT" => getenv("DB_PORT") ?: "5432",
          "DB_DATABASE" => getenv("DB_DATABASE"),
          "DB_USERNAME" => getenv("DB_USERNAME"),
          "DB_PASSWORD" => getenv("DB_PASSWORD"),
          "DB_SSLMODE" => getenv("DB_SSLMODE") ?: "require",
      ];

      $output = [];
      foreach ($contents as $line) {
          if (preg_match("/^([A-Za-z_][A-Za-z0-9_]*)=/", $line, $matches)) {
              $key = $matches[1];
              if (array_key_exists($key, $updates)) {
                  continue;
              }
          }
          $output[] = $line;
      }

      foreach ($updates as $key => $value) {
          if ($value === false || $value === null) {
              continue;
          }
          $output[] = $key . "=" . $value;
      }

      file_put_contents($envPath, implode("\n", $output) . "\n");
    '

    if [ -n "$APP_KEY" ]; then
      if grep -q "^APP_KEY=" .env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env
      else
        echo "APP_KEY=$APP_KEY" >> .env
      fi
    fi
  fi

  # Ensure DB_CONNECTION is pgsql and DB_PORT is set in .env if it exists.
  if grep -q "^DB_CONNECTION=" .env; then
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
  else
    echo "DB_CONNECTION=pgsql" >> .env
  fi

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

echo "Waiting for DB_HOST=$DB_HOST to resolve..."
if [ -z "$DB_HOST" ]; then
  echo "WARNING: DB_HOST is empty - skipping DNS resolution check"
else
  RETRY_DNS=0
  until [ "$RETRY_DNS" -ge 30 ]; do
    if getent hosts "$DB_HOST" >/dev/null 2>&1; then
      echo "✓ DB_HOST resolved: $DB_HOST"
      break
    fi
    RETRY_DNS=$((RETRY_DNS + 1))
    echo "Waiting for DB_HOST DNS resolution ($RETRY_DNS/30)..."
    sleep 2
  done
  if [ "$RETRY_DNS" -ge 30 ]; then
    echo "WARNING: DB_HOST did not resolve after 30 attempts: $DB_HOST (continuing anyway)"
  fi
fi

if [ -z "${APP_KEY:-}" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --ansi --force
  echo "✓ APP_KEY generated successfully"
else
  echo "✓ APP_KEY already present in environment (skipping generation)"
fi

echo "Clearing stale caches before config cache..."
php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

echo "Checking PHP extension support..."
php -r 'if (!extension_loaded("pdo") || !extension_loaded("pdo_pgsql")) { fwrite(STDERR, "ERROR: Required PHP extensions missing: pdo or pdo_pgsql\n"); exit(1);} print_r(PDO::getAvailableDrivers());'

echo "Caching configuration..."
if php artisan config:cache 2>&1; then
  echo "✓ Configuration cached successfully"
else
  echo "WARNING: config:cache failed, continuing anyway..."
fi

# Run migrations only (critical for app to start)
echo "Starting database migrations..."
RETRY_COUNT=0
MIGRATE_SUCCESS=0
until php artisan migrate --force 2>&1; do
  RETRY_COUNT=$((RETRY_COUNT + 1))
  if [ "$RETRY_COUNT" -ge 3 ]; then
    echo "WARNING: Failed to run migrations after $RETRY_COUNT attempts."
    echo "Will start Apache anyway - database may be unavailable."
    MIGRATE_SUCCESS=1
    break
  fi
  echo "Database not ready yet, retrying migrations in 3 seconds... ($RETRY_COUNT/3)"
  sleep 3
done

if [ "$MIGRATE_SUCCESS" -eq 0 ]; then
  echo "✓ Migrations completed successfully."
fi

# Try seeding but always continue regardless of result
echo "Attempting optional database seeding..."
if php artisan db:seed --force 2>&1 | head -10; then
  echo "Database seeding completed successfully."
else
  SEED_EXIT_CODE=$?
  echo "WARNING: Database seeding failed with exit code $SEED_EXIT_CODE (this is optional - continuing)"
fi

echo ""
echo "=========================================="
echo "✓ Initialization complete - starting Apache"
echo "=========================================="
echo ""

# Start Apache and keep it running
exec apache2-foreground
