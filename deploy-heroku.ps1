#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Deploy Laravel Cinema App to Heroku using Container Registry
.DESCRIPTION
    Automates the process of building, pushing, and releasing Docker container to Heroku
.PARAMETER AppName
    Heroku app name (default: appdemoheroku3)
.PARAMETER WithMigration
    Run database migrations after deployment
.PARAMETER WithSeed
    Run database seeder after deployment
.PARAMETER Logs
    Show application logs after deployment
.EXAMPLE
    .\deploy-heroku.ps1
    .\deploy-heroku.ps1 -AppName appdemoheroku3 -WithMigration
    .\deploy-heroku.ps1 -AppName appdemoheroku3 -WithMigration -WithSeed -Logs
#>

param(
    [string]$AppName = "appdemoheroku3",
    [switch]$WithMigration = $false,
    [switch]$WithSeed = $false,
    [switch]$Logs = $true,
    [int]$LogLines = 50
)

$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"

# Colors
$ColorGreen = [System.ConsoleColor]::Green
$ColorYellow = [System.ConsoleColor]::Yellow
$ColorCyan = [System.ConsoleColor]::Cyan
$ColorRed = [System.ConsoleColor]::Red

function Write-Status {
    param([string]$Message, [string]$Color = "Cyan")
    Write-Host $Message -ForegroundColor $Color
}

function Write-Step {
    param([string]$Number, [string]$Message)
    Write-Host "`n[$Number] $Message" -ForegroundColor $ColorCyan
    Write-Host ("-" * 70) -ForegroundColor Gray
}

function Check-Command {
    param([string]$Command)
    try {
        if (-not (Get-Command $Command -ErrorAction Stop)) {
            return $false
        }
        return $true
    }
    catch {
        return $false
    }
}

# Main deployment flow
try {
    Write-Host @"
╔════════════════════════════════════════════════════════════════════╗
║        🚀 HEROKU CONTAINER REGISTRY DEPLOYMENT SCRIPT 🚀           ║
║                                                                    ║
║  App Name: $AppName
║  Migration: $(if($WithMigration) { "Enabled ✅" } else { "Disabled ❌" })
║  Database Seed: $(if($WithSeed) { "Enabled ✅" } else { "Disabled ❌" })
║  Show Logs: $(if($Logs) { "Enabled ✅" } else { "Disabled ❌" })
╚════════════════════════════════════════════════════════════════════╝
"@ -ForegroundColor $ColorGreen

    # Check prerequisites
    Write-Step "1" "Checking prerequisites"
    
    if (-not (Check-Command "heroku")) {
        Write-Status "❌ Heroku CLI not found. Install from: https://devcenter.heroku.com/articles/heroku-cli" -Color $ColorRed
        exit 1
    }
    Write-Status "✅ Heroku CLI found"
    
    if (-not (Check-Command "docker")) {
        Write-Status "❌ Docker not found. Make sure Docker Desktop is installed and running." -Color $ColorRed
        exit 1
    }
    Write-Status "✅ Docker found"
    
    # Verify Docker is running
    Write-Status "Checking if Docker daemon is running..."
    docker info | Out-Null
    if ($LASTEXITCODE -ne 0) {
        Write-Status "❌ Docker daemon is not running. Please start Docker Desktop." -Color $ColorRed
        exit 1
    }
    Write-Status "✅ Docker daemon is running"

    # Step 1: Login to Heroku Container Registry
    Write-Step "2" "Logging into Heroku Container Registry"
    heroku container:login
    if ($LASTEXITCODE -ne 0) {
        Write-Status "❌ Failed to login to Heroku Container Registry" -Color $ColorRed
        exit 1
    }
    Write-Status "✅ Successfully logged in to Heroku Container Registry" -Color $ColorGreen

    # Step 2: Build and Push
    Write-Step "3" "Building and pushing Docker image to Heroku"
    Write-Status "This may take several minutes..." -Color $ColorYellow
    
    $startTime = Get-Date
    heroku container:push web -a $AppName
    if ($LASTEXITCODE -ne 0) {
        Write-Status "❌ Failed to push Docker image" -Color $ColorRed
        exit 1
    }
    $buildTime = (Get-Date) - $startTime
    Write-Status "✅ Docker image pushed successfully (took $($buildTime.TotalSeconds) seconds)" -Color $ColorGreen

    # Step 3: Release
    Write-Step "4" "Releasing container on Heroku"
    heroku container:release web -a $AppName
    if ($LASTEXITCODE -ne 0) {
        Write-Status "❌ Failed to release container" -Color $ColorRed
        exit 1
    }
    Write-Status "✅ Container released successfully" -Color $ColorGreen

    # Step 4: Run migrations if requested
    if ($WithMigration) {
        Write-Step "5" "Running database migrations"
        Write-Status "Executing: php artisan migrate --force" -Color $ColorYellow
        heroku run "php artisan migrate --force" -a $AppName
        if ($LASTEXITCODE -ne 0) {
            Write-Status "⚠️  Migrations failed - check logs for details" -Color $ColorYellow
        }
        else {
            Write-Status "✅ Database migrations completed successfully" -Color $ColorGreen
        }
    }

    # Step 5: Run seed if requested
    if ($WithSeed) {
        Write-Step "6" "Running database seeder"
        Write-Status "Executing: php artisan db:seed --force" -Color $ColorYellow
        heroku run "php artisan db:seed --force" -a $AppName
        if ($LASTEXITCODE -ne 0) {
            Write-Status "⚠️  Database seeding failed - check logs for details" -Color $ColorYellow
        }
        else {
            Write-Status "✅ Database seeding completed successfully" -Color $ColorGreen
        }
    }

    # Step 6: Show logs if requested
    if ($Logs) {
        Write-Step "7" "Fetching application logs (last $LogLines lines)"
        heroku logs -n $LogLines -a $AppName
    }

    # Summary
    Write-Host "`n╔════════════════════════════════════════════════════════════════════╗" -ForegroundColor $ColorGreen
    Write-Host "║                    ✅ DEPLOYMENT SUCCESSFUL! ✅                      ║" -ForegroundColor $ColorGreen
    Write-Host "╚════════════════════════════════════════════════════════════════════╝" -ForegroundColor $ColorGreen
    
    Write-Host @"

📱 Your app is now live at: https://$AppName.herokuapp.com

📋 Useful commands:
   heroku logs --tail -a $AppName           # View real-time logs
   heroku config -a $AppName                # View environment variables
   heroku config:set KEY=VALUE -a $AppName # Set environment variable
   heroku restart -a $AppName               # Restart the app
   heroku container:ls -a $AppName          # List deployed containers

❓ Need help?
   heroku help container                    # Container Registry help
   heroku apps:info -a $AppName             # App information

" -ForegroundColor $ColorGreen
}
catch {
    Write-Status "❌ Error: $_" -Color $ColorRed
    exit 1
}
