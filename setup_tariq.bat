@echo off
REM TARIQ Database Setup Script for Windows
setlocal enabledelayedexpansion

cd /d "c:\xampp\htdocs\tariq"

echo ===== TARIQ Database Setup =====
echo.

REM Set environment variables
set DATABASE_URL=postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require^&channel_binding=require
set DB_CONNECTION=pgsql
set DB_HOST=ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech
set DB_PORT=5432
set DB_DATABASE=TARIQ
set DB_USERNAME=neondb_owner
set DB_PASSWORD=npg_ykgfTMnI42qV
set DB_SSLMODE=require

echo [1/5] Environment variables configured for TARIQ
echo   Database: !DB_DATABASE!
echo   Host: !DB_HOST!
echo.

echo [2/5] Clearing caches...
"C:\xampp\php\php.exe" artisan config:clear
"C:\xampp\php\php.exe" artisan route:clear
"C:\xampp\php\php.exe" artisan cache:clear
echo OK
echo.

echo [3/5] Running migrations...
"C:\xampp\php\php.exe" artisan migrate --force
if errorlevel 1 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)
echo OK
echo.

echo [4/5] Seeding database...
"C:\xampp\php\php.exe" artisan db:seed --force
if errorlevel 1 (
    echo WARNING: Seeding had issues, but continuing...
)
echo OK
echo.

echo [5/5] Checking migration status...
"C:\xampp\php\php.exe" artisan migrate:status
echo.

echo ===== Setup Complete =====
echo TARIQ database is ready!
pause
