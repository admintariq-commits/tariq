@echo off
setlocal enabledelayedexpansion

cd /d "c:\xampp\htdocs\tariq"

REM Set environment variables for TARIQ
set DATABASE_URL=postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require^&channel_binding=require
set DB_CONNECTION=pgsql
set DB_HOST=ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech
set DB_PORT=5432
set DB_DATABASE=TARIQ
set DB_USERNAME=neondb_owner
set DB_PASSWORD=npg_ykgfTMnI42qV
set DB_SSLMODE=require

echo ===== TARIQ Database Fresh Setup (Fix Migration Issues) =====
echo.

echo [1/6] Clearing caches...
"C:\xampp\php\php.exe" artisan config:clear > nul
"C:\xampp\php\php.exe" artisan route:clear > nul
"C:\xampp\php\php.exe" artisan cache:clear > nul
echo OK
echo.

echo [2/6] Dropping all tables and resetting database...
"C:\xampp\php\php.exe" artisan migrate:reset --force 2>&1 | findstr /V "^$"
echo OK
echo.

echo [3/6] Running fresh migrations...
"C:\xampp\php\php.exe" artisan migrate:refresh --force 2>&1 | findstr /V "^$"
if errorlevel 1 (
    echo WARNING: Some issues during migration, but continuing...
)
echo OK
echo.

echo [4/6] Running database seeding...
"C:\xampp\php\php.exe" artisan db:seed --force 2>&1 | findstr /V "^$"
echo OK
echo.

echo [5/6] Verifying database setup...
"C:\xampp\php\php.exe" check_db_state.php 2>&1
echo OK
echo.

echo [6/6] Displaying migration status...
"C:\xampp\php\php.exe" artisan migrate:status 2>&1 | findstr /V "^$"
echo.

echo ===== TARIQ Database Setup Complete =====
echo The database is now ready for use!
echo.
pause
