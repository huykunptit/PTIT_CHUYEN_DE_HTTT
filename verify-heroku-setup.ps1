#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Pre-deployment checklist for Heroku deployment
.DESCRIPTION
    Validates all prerequisites and configurations before deploying to Heroku
#>

$ErrorActionPreference = "SilentlyContinue"

# Colors
$ColorGreen = [System.ConsoleColor]::Green
$ColorRed = [System.ConsoleColor]::Red
$ColorYellow = [System.ConsoleColor]::Yellow
$ColorCyan = [System.ConsoleColor]::Cyan

function Test-Requirement {
    param(
        [string]$Name,
        [scriptblock]$Test,
        [string]$FixCommand = $null
    )
    
    $result = & $Test
    $status = if ($result) { "✅ PASS" } else { "❌ FAIL" }
    $color = if ($result) { $ColorGreen } else { $ColorRed }
    
    Write-Host "  $status - $Name" -ForegroundColor $color
    
    if (-not $result -and $FixCommand) {
        Write-Host "    💡 Fix: $FixCommand" -ForegroundColor $ColorYellow
    }
    
    return $result
}

Write-Host "`n╔════════════════════════════════════════════════════════════════════╗" -ForegroundColor $ColorCyan
Write-Host "║          📋 HEROKU DEPLOYMENT PRE-FLIGHT CHECKLIST 📋              ║" -ForegroundColor $ColorCyan
Write-Host "╚════════════════════════════════════════════════════════════════════╝`n" -ForegroundColor $ColorCyan

$allPass = $true

# Check 1: Tools Installation
Write-Host "1️⃣ REQUIRED TOOLS" -ForegroundColor $ColorCyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$allPass = (Test-Requirement -Name "Heroku CLI installed" -Test {
    (Get-Command heroku -ErrorAction SilentlyContinue) -ne $null
} -FixCommand "Visit: https://devcenter.heroku.com/articles/heroku-cli") -and $allPass

$allPass = (Test-Requirement -Name "Docker installed" -Test {
    (Get-Command docker -ErrorAction SilentlyContinue) -ne $null
} -FixCommand "Install Docker Desktop: https://www.docker.com/products/docker-desktop") -and $allPass

$allPass = (Test-Requirement -Name "Docker daemon running" -Test {
    docker info 2>$null | Select-String "Containers" | Measure-Object | Select-Object -ExpandProperty Count -gt 0
} -FixCommand "Start Docker Desktop or Docker daemon") -and $allPass

# Check 2: Heroku Authentication
Write-Host "`n2️⃣ HEROKU AUTHENTICATION" -ForegroundColor $ColorCyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$allPass = (Test-Requirement -Name "Logged in to Heroku" -Test {
    $auth = heroku auth:whoami 2>$null
    $auth -and $auth.Length -gt 0
} -FixCommand "Run: heroku login") -and $allPass

# Check 3: Heroku App
Write-Host "`n3️⃣ HEROKU APP CONFIGURATION" -ForegroundColor $ColorCyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$AppName = "appdemoheroku3"
$appExists = Test-Requirement -Name "App '$AppName' exists on Heroku" -Test {
    heroku apps --json 2>$null | ConvertFrom-Json | Where-Object { $_.name -eq $AppName } | Measure-Object | Select-Object -ExpandProperty Count -gt 0
} -FixCommand "Create app: heroku create $AppName"
$allPass = $appExists -and $allPass

# Check 4: Laravel Configuration
Write-Host "`n4️⃣ LARAVEL CONFIGURATION" -ForegroundColor $ColorCyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$allPass = (Test-Requirement -Name "composer.json exists" -Test {
    Test-Path "composer.json"
} -FixCommand "Ensure you're in the project root directory") -and $allPass

$allPass = (Test-Requirement -Name ".env file exists (or .env.example)" -Test {
    (Test-Path ".env") -or (Test-Path ".env.example")
} -FixCommand "Copy: cp .env.example .env") -and $allPass

$allPass = (Test-Requirement -Name "Dockerfile exists" -Test {
    (Test-Path "Dockerfile") -or (Test-Path "Dockerfile.heroku")
} -FixCommand "Dockerfile is required for Container Registry deployment") -and $allPass

$allPass = (Test-Requirement -Name "Procfile exists" -Test {
    Test-Path "Procfile"
} -FixCommand "Create Procfile in project root") -and $allPass

# Check 5: Environment Variables
Write-Host "`n5️⃣ HEROKU ENVIRONMENT VARIABLES" -ForegroundColor $ColorCyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

if ($appExists) {
    $config = heroku config -a $AppName --json 2>$null | ConvertFrom-Json
    
    $appKey = $config.APP_KEY
    $appDebug = $config.APP_DEBUG
    $appEnv = $config.APP_ENV
    $dbUrl = $config.DATABASE_URL
    
    Test-Requirement -Name "APP_KEY is set" -Test { $appKey -and $appKey.Length -gt 0 } -FixCommand "Run: heroku config:set APP_KEY=your_key -a $AppName" | Out-Null
    
    Test-Requirement -Name "APP_ENV is set (should be 'production')" -Test { $appEnv -eq "production" } -FixCommand "Run: heroku config:set APP_ENV=production -a $AppName" | Out-Null
    
    Test-Requirement -Name "APP_DEBUG is false" -Test { $appDebug -eq "false" } -FixCommand "Run: heroku config:set APP_DEBUG=false -a $AppName" | Out-Null
    
    Test-Requirement -Name "DATABASE_URL is configured" -Test { $dbUrl -and $dbUrl.Length -gt 0 } -FixCommand "Configure your database add-on or set DATABASE_URL manually" | Out-Null
    
    Write-Host "`n📦 Current Config on Heroku:" -ForegroundColor $ColorCyan
    heroku config -a $AppName | Write-Host
}
else {
    Write-Host "  ⚠️  Cannot check config - app not created yet" -ForegroundColor $ColorYellow
}

# Check 6: Docker Image Build Test
Write-Host "`n6️⃣ DOCKER BUILD TEST (OPTIONAL)" -ForegroundColor $ColorCyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Gray

$response = Read-Host "Test Docker build locally? (y/n)"
if ($response -eq 'y' -or $response -eq 'Y') {
    Write-Host "  🔨 Building Docker image (this may take 5-10 minutes)..." -ForegroundColor $ColorYellow
    docker build -t cinema-app:test .
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  ✅ Docker build test PASSED" -ForegroundColor $ColorGreen
    }
    else {
        Write-Host "  ❌ Docker build test FAILED - fix issues before deploying" -ForegroundColor $ColorRed
        $allPass = $false
    }
}

# Summary
Write-Host "`n╔════════════════════════════════════════════════════════════════════╗" -ForegroundColor $ColorCyan

if ($allPass) {
    Write-Host "║                    ✅ ALL CHECKS PASSED! ✅                        ║" -ForegroundColor $ColorGreen
    Write-Host "╚════════════════════════════════════════════════════════════════════╝" -ForegroundColor $ColorGreen
    
    Write-Host @"

🚀 You're ready to deploy! Next steps:

  1. Deploy to Heroku using Container Registry:
     .\deploy-heroku.ps1

  OR manually:
     heroku container:login
     heroku container:push web -a $AppName
     heroku container:release web -a $AppName

  2. Monitor logs:
     heroku logs --tail -a $AppName

  3. Run migrations (if needed):
     heroku run "php artisan migrate --force" -a $AppName

"@ -ForegroundColor $ColorGreen
}
else {
    Write-Host "║                    ❌ ISSUES FOUND - FIX BEFORE DEPLOY ❌           ║" -ForegroundColor $ColorRed
    Write-Host "╚════════════════════════════════════════════════════════════════════╝" -ForegroundColor $ColorRed
    
    Write-Host "`n🔧 Fix the issues above and run this checklist again.`n" -ForegroundColor $ColorYellow
    exit 1
}

Write-Host ""
