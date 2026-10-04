@echo off
title ARPOS - Ahmed Restaurant POS
echo ============================================
echo  ARPOS - Ahmed Restaurant POS
echo  "Serve Every Order With Precision."
echo  Developed by Ahmed
echo ============================================
echo.

where php >nul 2>nul || (echo ERROR: PHP not found. Install PHP 8.2+ & goto :end)
where composer >nul 2>nul || (echo ERROR: Composer not found. & goto :end)
where node >nul 2>nul || (echo ERROR: Node.js not found. & goto :end)

cd backend
if not exist vendor (echo Installing backend deps... & call composer install)
if not exist .env (copy .env.example .env & php artisan key:generate & php artisan jwt:secret)
if not exist database\database.sqlite (type nul > database\database.sqlite)
php artisan migrate --seed --force
start "ARPOS Backend" php artisan serve --host=127.0.0.1 --port=8001
cd ..\frontend
if not exist node_modules (echo Installing frontend deps... & call npm install)
if not exist .env (echo VITE_API_BASE_URL=http://127.0.0.1:8001/api/v1 > .env)
echo.
echo Backend:  http://127.0.0.1:8001
echo Frontend: http://127.0.0.1:5174
echo Login: owner@ahmedfoods.local / password123
echo.
call npm run dev -- --host 127.0.0.1 --port 5174
:end
pause
