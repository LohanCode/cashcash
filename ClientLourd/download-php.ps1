$phpUrl = "https://windows.php.net/downloads/releases/php-8.2.30-nts-Win32-vs16-x64.zip"
$zipPath = "php-8.2.zip"
$extractPath = "php-win"

Write-Host "Downloading PHP..."
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
Invoke-WebRequest -Uri $phpUrl -OutFile $zipPath

Write-Host "Extracting PHP..."
if (Test-Path $extractPath) {
    Remove-Item -Recurse -Force $extractPath
}
Expand-Archive -Path $zipPath -DestinationPath $extractPath -Force

Write-Host "Configuring PHP..."
$phpIniPath = Join-Path $extractPath "php.ini"
Copy-Item (Join-Path $extractPath "php.ini-production") $phpIniPath

# Enable required extensions
$iniContent = Get-Content $phpIniPath
$iniContent = $iniContent -replace ';extension_dir = "ext"', 'extension_dir = "ext"'
$iniContent = $iniContent -replace ';extension=pdo_sqlite', 'extension=pdo_sqlite'
$iniContent = $iniContent -replace ';extension=sqlite3', 'extension=sqlite3'
$iniContent = $iniContent -replace ';extension=fileinfo', 'extension=fileinfo'
$iniContent = $iniContent -replace ';extension=mbstring', 'extension=mbstring'
$iniContent = $iniContent -replace ';extension=openssl', 'extension=openssl'
$iniContent = $iniContent -replace ';extension=pdo_mysql', 'extension=pdo_mysql' # Just in case
Set-Content -Path $phpIniPath -Value $iniContent

Write-Host "Cleaning up..."
Remove-Item $zipPath

Write-Host "Done!"
