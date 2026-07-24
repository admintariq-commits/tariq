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
          "DB_CONNECTION" => getenv("DB_CONNECTION"),
          "DB_HOST" => getenv("DB_HOST"),
          "DB_PORT" => getenv("DB_PORT"),
          "DB_DATABASE" => getenv("DB_DATABASE"),
          "DB_USERNAME" => getenv("DB_USERNAME"),
          "DB_PASSWORD" => getenv("DB_PASSWORD"),
          "DB_SSLMODE" => getenv("DB_SSLMODE"),
      ];
      foreach ($updates as $key => $value) {
          if ($value === false) {
              continue;
          }
          $found = false;
          foreach ($contents as &$line) {
              if (strpos($line, $key . "=") === 0) {
                  $line = $key . "=" . $value;
                  $found = true;
                  break;
              }
          }
          if (! $found) {
              $contents[] = $key . "=" . $value;
          }
      }
      file_put_contents($envPath, implode("\n", $contents) . "\n");
    '
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
RETRY_DNS=0
until [ $RETRY_DNS -ge 30 ]; do
  if getent hosts "$DB_HOST" >/dev/null 2>&1; then
    echo "DB_HOST resolved: $DB_HOST"
    break
  fi
  RETRY_DNS=$((RETRY_DNS + 1))
  echo "Waiting for DB_HOST DNS resolution ($RETRY_DNS/30)..."
  sleep 2
done
if [ $RETRY_DNS -ge 30 ]; then
  echo "ERROR: DB_HOST did not resolve after 30 attempts: $DB_HOST"
fi

if [ -z "${APP_KEY:-}" ]; then
  php artisan key:generate --ansi --force
else
  echo "APP_KEY already present in the environment; skipping key generation"
fi
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
