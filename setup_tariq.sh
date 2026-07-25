#!/bin/bash
set -e

echo "=== TARIQ Database Setup and Migration ==="
echo ""

# Step 1: Verify environment variables
echo "[1/4] Verifying environment configuration..."
export DATABASE_URL="postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require&channel_binding=require"
export DB_CONNECTION=pgsql
export DB_HOST=ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech
export DB_PORT=5432
export DB_DATABASE=TARIQ
export DB_USERNAME=neondb_owner
export DB_PASSWORD=npg_ykgfTMnI42qV
export DB_SSLMODE=require

echo "✓ Environment variables set for TARIQ database"
echo "  DATABASE: $DB_DATABASE"
echo "  HOST: $DB_HOST"
echo ""

# Step 2: Clear Laravel caches
echo "[2/4] Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
echo "✓ Caches cleared"
echo ""

# Step 3: Run migrations
echo "[3/4] Running database migrations..."
php artisan migrate --force
echo "✓ Migrations completed"
echo ""

# Step 4: Seed the database
echo "[4/4] Seeding the database..."
php artisan db:seed --force
echo "✓ Database seeded"
echo ""

echo "=== Setup Complete ==="
echo "✓ TARIQ database is ready!"
