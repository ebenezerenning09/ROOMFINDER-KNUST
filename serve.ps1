# Start RoomFinder — run this from room-finder folder (or double-click if allowed)
$php = "C:\Users\USER\ROOMFINDER-KNUST\.tools\php\php.exe"
Set-Location $PSScriptRoot

Write-Host ""
Write-Host "  RoomFinder dev server" -ForegroundColor Green
Write-Host "  Open site:  http://127.0.0.1:8000/rooms" -ForegroundColor Cyan
Write-Host "  Open admin: http://127.0.0.1:8000/admin/login" -ForegroundColor Cyan
Write-Host "  (Run 'npm run build' if styles look broken)" -ForegroundColor DarkGray
Write-Host "  Press Ctrl+C to stop" -ForegroundColor DarkGray
Write-Host ""

& $php -d max_execution_time=120 artisan serve --host=127.0.0.1 --port=8000
