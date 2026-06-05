@echo off
cd /d c:\xampp\htdocs\tariq
git add -f .env
git commit -m "fix: SESSION_DRIVER to file for Render compatibility"
git push admintariq main
pause
