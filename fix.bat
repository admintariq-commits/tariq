@echo off
setlocal

cd /d "c:\xampp\htdocs\tariq"

REM Set TARIQ database environment
set DATABASE_URL=postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require^&channel_binding=require
set DB_CONNECTION=pgsql
set DB_HOST=ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech
set DB_PORT=5432
set DB_DATABASE=TARIQ
set DB_USERNAME=neondb_owner
set DB_PASSWORD=npg_ykgfTMnI42qV
set DB_SSLMODE=require

"C:\xampp\php\php.exe" fix_db.php
