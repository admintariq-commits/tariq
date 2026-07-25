@echo off
cd /d "c:\xampp\htdocs\tariq"
"C:\xampp\php\php.exe" check_db_state.php > db_state_output.txt 2>&1
echo Migration rollback...
"C:\xampp\php\php.exe" artisan migrate:rollback --force > rollback_output.txt 2>&1
echo Fresh migrations...
"C:\xampp\php\php.exe" artisan migrate:refresh --force > refresh_output.txt 2>&1
