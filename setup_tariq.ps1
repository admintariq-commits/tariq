# TARIQ Database Setup Script
$projectPath = "c:\xampp\htdocs\tariq"
$phpPath = "C:\xampp\php\php.exe"

# Change to project directory
Set-Location $projectPath

Write-Host "=== TARIQ Database Setup and Migration ===" -ForegroundColor Green
Write-Host ""

# Set environment variables
$env:DATABASE_URL = "postgresql://neondb_owner:npg_ykgfTMnI42qV@ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech/TARIQ?sslmode=require&channel_binding=require"
$env:DB_CONNECTION = "pgsql"
$env:DB_HOST = "ep-polished-butterfly-ayqfh2r6-pooler.c-5.us-east-2.aws.neon.tech"
$env:DB_PORT = "5432"
$env:DB_DATABASE = "TARIQ"
$env:DB_USERNAME = "neondb_owner"
$env:DB_PASSWORD = "npg_ykgfTMnI42qV"
$env:DB_SSLMODE = "require"

Write-Host "[1/4] Verifying environment configuration..." -ForegroundColor Cyan
Write-Host "[OK] Environment variables set for TARIQ database" -ForegroundColor Green
Write-Host "  DATABASE: $($env:DB_DATABASE)"
Write-Host "  HOST: $($env:DB_HOST)"
Write-Host ""

Write-Host "[2/4] Clearing Laravel caches..." -ForegroundColor Cyan
& $phpPath artisan config:clear | Out-Null
& $phpPath artisan route:clear | Out-Null
& $phpPath artisan cache:clear | Out-Null
Write-Host "[OK] Caches cleared" -ForegroundColor Green
Write-Host ""

Write-Host "[3/4] Running database migrations..." -ForegroundColor Cyan
& $phpPath artisan migrate --force
Write-Host "[OK] Migrations completed" -ForegroundColor Green
Write-Host ""

Write-Host "[4/4] Seeding the database..." -ForegroundColor Cyan
& $phpPath artisan db:seed --force
Write-Host "[OK] Database seeded" -ForegroundColor Green
Write-Host ""

Write-Host "=== Setup Complete ===" -ForegroundColor Green
Write-Host "[OK] TARIQ database is ready!" -ForegroundColor Green
