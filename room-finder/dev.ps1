$env:Path = "$PSScriptRoot\..\.tools\php;$PSScriptRoot\..\.tools;" + $env:Path
Set-Location $PSScriptRoot
npx concurrently -c "#93c5fd,#c4b5fd" "php artisan serve" "npm run dev" --names=server,vite --kill-others
