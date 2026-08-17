@echo off
set "PATH=%~dp0..\.tools\php;%~dp0..\.tools;%PATH%"
cd /d "%~dp0"
npx concurrently -c "#93c5fd,#c4b5fd" "php artisan serve" "npm run dev" --names=server,vite --kill-others
