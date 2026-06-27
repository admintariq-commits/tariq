$ErrorActionPreference = 'Stop'
cd c:\xampp\htdocs\tariq

Write-Host "Adding files..." -ForegroundColor Green
git add app/Http/Controllers/Auth/RegisterController.php routes/web.php

Write-Host "Committing..." -ForegroundColor Green
git commit -m "Configure OTP for local dev: skip verification locally, enable for production; add config clear endpoint"

Write-Host "Pushing to GitHub..." -ForegroundColor Green
git push admintariq main

Write-Host "Done!" -ForegroundColor Green
