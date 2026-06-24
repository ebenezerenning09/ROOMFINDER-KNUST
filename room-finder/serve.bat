@echo off
set "PATH=%~dp0..\.tools\php;%~dp0..\.tools;%PATH%"
cd /d "%~dp0"
php artisan serve
