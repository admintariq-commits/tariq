@echo off
cd C:\xampp\htdocs\tariq
git add resources/views/auth/graduate-register.blade.php
git commit -m "Fix Alpine.js registration UI: move registrationForm to head, add fallback inputs for university/course, fix variable naming conflict"
git push admintariq main
echo.
echo Push completed! Check GitHub for the commit.
pause
