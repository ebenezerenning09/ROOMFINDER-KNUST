$env:Path = "$PSScriptRoot\..\.tools\php;$PSScriptRoot\..\.tools;" + $env:Path
Set-Location $PSScriptRoot
php artisan serve
