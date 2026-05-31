param(
    [string]$ProjectPath = "$PSScriptRoot"
)

Set-Location $ProjectPath
Write-Host "=== Tariq production deployment helper ==="

if (-not (Test-Path .git -PathType Container)) {
    Write-Error "This directory is not a Git repository."
    exit 1
}

if (-not (Test-Path .env -PathType Leaf)) {
    Write-Host ".env file not found. Copying .env.production.example to .env"
    Copy-Item .env.production.example .env
    Write-Host "Please edit .env with your production values before continuing."
    exit 0
}

Write-Host "Updating repo from origin/main..."
git fetch origin main

git reset --hard origin/main

Write-Host "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

Write-Host "Generating app key if missing..."
$envContent = Get-Content .env
if ($envContent -notmatch '^APP_KEY=.*[^=]$') {
    php artisan key:generate --force
}

Write-Host "Running migrations..."
php artisan migrate --force

Write-Host "Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "Building frontend assets..."
npm install
npm run build

Write-Host "Deployment helper finished. Verify your web server configuration and restart PHP/FPM if needed."
