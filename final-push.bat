@echo off
cd /d c:\xampp\htdocs\tariq
echo Committing changes...
git add app/Http/Controllers/Auth/RegisterController.php routes/web.php
git commit -m "Configure OTP for local dev: skip verification locally, enable for production; add config clear endpoint"
echo Pushing to GitHub...
git push admintariq main
echo Done!
pause
