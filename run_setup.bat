@echo off
setlocal enabledelayedexpansion

cd /d "c:\xampp\htdocs\tariq"

REM Set environment variables
set DATABASE_URL=postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require^&channel_binding=require
set DB_CONNECTION=pgsql
set DB_HOST=ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech
set DB_PORT=5432
set DB_DATABASE=TARIQ
set DB_USERNAME=neondb_owner
set DB_PASSWORD=npg_ykgfTMnI42qV
set DB_SSLMODE=require
set LARAVEL_ENV=production

echo Running TARIQ Database Setup...
"C:\xampp\php\php.exe" setup_database.php
echo.
echo Setup script completed. Check output above for any errors.
pause
