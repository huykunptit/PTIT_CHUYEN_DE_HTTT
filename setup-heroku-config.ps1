#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Initialize Heroku app configuration for appdemoheroku3
.DESCRIPTION
    Sets up all required environment variables and configurations for the Cinema Booking app
    Run this BEFORE deploying
.EXAMPLE
    .\setup-heroku-config.ps1
    .\setup-heroku-config.ps1 -AppName appdemoheroku3 -Interactive
#>

param(
    [string]$AppName = "appdemoheroku3",
    [switch]$Interactive = $false,
    [string]$AppKey = $null,
    [string]$DatabaseUrl = $null
)

$ErrorActionPreference = "Stop"

# Colors
$Green = [System.ConsoleColor]::Green
$Yellow = [System.ConsoleColor]::Yellow
$Cyan = [System.ConsoleColor]::Cyan
$Red = [System.ConsoleColor]::Red

function Write-Header {
    param([string]$Text)
    Write-Host "`n╔════════════════════════════════════════════════════════════════════╗" -ForegroundColor $Cyan
    Write-Host "║  $Text" -ForegroundColor $Cyan
    Write-Host "╚════════════════════════════════════════════════════════════════════╝" -ForegroundColor $Cyan
}

function Set-Config {
    param(
        [string]$Key,
        [string]$Value,
        [string]$Description = $null
    )
    
    if ($Description) {
        Write-Host "  📝 $Description" -ForegroundColor $Yellow
    }
    
    Write-Host "  ⚙️  Setting $Key..." -ForegroundColor $Cyan
    heroku config:set "$Key=$Value" -a $AppName | Out-Null
    
    Write-Host "  ✅ $Key is set" -ForegroundColor $Green
}

try {
    Write-Header "HEROKU CONFIGURATION SETUP - $AppName"
    
    # Check if app exists
    Write-Host "`nVerifying app exists..." -ForegroundColor $Cyan
    $appInfo = heroku apps:info -a $AppName --json 2>&1 | ConvertFrom-Json -ErrorAction SilentlyContinue
    if (-not $appInfo.id) {
        Write-Host "❌ App '$AppName' not found on Heroku" -ForegroundColor $Red
        Write-Host "Create it first: heroku create $AppName" -ForegroundColor $Yellow
        exit 1
    }
    Write-Host "✅ App '$AppName' found" -ForegroundColor $Green
    
    # ESSENTIAL CONFIGURATION
    Write-Header "1. ESSENTIAL CONFIGURATION"
    
    # APP_NAME
    Set-Config -Key "APP_NAME" -Value "Cinema Booking" -Description "Application name"
    
    # APP_ENV
    Set-Config -Key "APP_ENV" -Value "production" -Description "Environment (must be 'production')"
    
    # APP_DEBUG
    Set-Config -Key "APP_DEBUG" -Value "false" -Description "Disable debug mode (security)"
    
    # APP_KEY
    Write-Host "`n  📝 Setting application encryption key..." -ForegroundColor $Yellow
    if ($AppKey) {
        Set-Config -Key "APP_KEY" -Value $AppKey
    }
    else {
        $generatedKey = Read-Host "  Enter your APP_KEY (from 'php artisan key:generate --show')"
        if (-not $generatedKey) {
            Write-Host "  ⚠️  APP_KEY is required! Generate locally: php artisan key:generate --show" -ForegroundColor $Red
        }
        else {
            Set-Config -Key "APP_KEY" -Value $generatedKey
        }
    }
    
    # APP_URL
    $appUrl = "https://$AppName.herokuapp.com"
    Set-Config -Key "APP_URL" -Value $appUrl -Description "Application URL"
    
    # DATABASE CONFIGURATION
    Write-Header "2. DATABASE CONFIGURATION"
    
    Write-Host "  Choose database setup method:" -ForegroundColor $Cyan
    Write-Host "  [1] Use Heroku PostgreSQL add-on" -ForegroundColor $Yellow
    Write-Host "  [2] Use external database (provide URL)" -ForegroundColor $Yellow
    Write-Host "  [3] Skip for now (configure later)" -ForegroundColor $Yellow
    
    if ($Interactive) {
        $dbChoice = Read-Host "  Select option (1-3)"
    }
    else {
        $dbChoice = "3"
    }
    
    switch ($dbChoice) {
        "1" {
            Write-Host "  📦 Creating Heroku PostgreSQL add-on (hobby-dev tier)..." -ForegroundColor $Cyan
            Write-Host "  ⚠️  This may cost money! Check pricing: heroku.com/pricing" -ForegroundColor $Red
            
            $confirm = if ($Interactive) {
                Read-Host "  Continue? (y/n)"
            }
            else {
                "n"
            }
            
            if ($confirm -eq 'y' -or $confirm -eq 'Y') {
                heroku addons:create heroku-postgresql:hobby-dev -a $AppName
                Write-Host "  ✅ PostgreSQL add-on created" -ForegroundColor $Green
            }
            else {
                Write-Host "  ⏭️  Skipping PostgreSQL setup" -ForegroundColor $Yellow
            }
        }
        "2" {
            if ($DatabaseUrl) {
                Set-Config -Key "DATABASE_URL" -Value $DatabaseUrl -Description "External database URL"
            }
            else {
                $dbUrl = Read-Host "  Enter DATABASE_URL"
                if ($dbUrl) {
                    Set-Config -Key "DATABASE_URL" -Value $dbUrl
                }
            }
        }
        default {
            Write-Host "  ⏭️  Database setup skipped" -ForegroundColor $Yellow
            Write-Host "  Configure later with: heroku config:set DATABASE_URL=... -a $AppName" -ForegroundColor $Cyan
        }
    }
    
    # CACHE & SESSION CONFIGURATION
    Write-Header "3. CACHE & SESSION CONFIGURATION"
    
    Write-Host "  Note: Heroku Redis add-on may have additional costs" -ForegroundColor $Yellow
    $useRedis = if ($Interactive) {
        Read-Host "  Use Redis for caching/sessions? (y/n)"
    }
    else {
        "n"
    }
    
    if ($useRedis -eq 'y' -or $useRedis -eq 'Y') {
        Write-Host "  📦 Creating Redis add-on..." -ForegroundColor $Cyan
        heroku addons:create heroku-redis:premium-0 -a $AppName
        
        if ($LASTEXITCODE -eq 0) {
            Set-Config -Key "CACHE_DRIVER" -Value "redis" -Description "Cache driver"
            Set-Config -Key "SESSION_DRIVER" -Value "cookie" -Description "Session driver (or 'redis')"
            Set-Config -Key "QUEUE_DRIVER" -Value "redis" -Description "Queue driver"
            Write-Host "  ✅ Redis configured" -ForegroundColor $Green
        }
    }
    else {
        Set-Config -Key "CACHE_DRIVER" -Value "array" -Description "Cache driver (in-memory)"
        Set-Config -Key "SESSION_DRIVER" -Value "cookie" -Description "Session driver (cookie-based)"
        Set-Config -Key "QUEUE_DRIVER" -Value "sync" -Description "Queue driver (synchronous)"
        Write-Host "  ℹ️  Using in-memory cache and cookie sessions" -ForegroundColor $Cyan
    }
    
    # MAIL CONFIGURATION
    Write-Header "4. MAIL CONFIGURATION"
    
    Write-Host "  Select mail driver:" -ForegroundColor $Cyan
    Write-Host "  [1] Mailtrap (for development)" -ForegroundColor $Yellow
    Write-Host "  [2] SendGrid" -ForegroundColor $Yellow
    Write-Host "  [3] Gmail (less secure)" -ForegroundColor $Yellow
    Write-Host "  [4] Skip for now" -ForegroundColor $Yellow
    
    if ($Interactive) {
        $mailChoice = Read-Host "  Select option (1-4)"
    }
    else {
        $mailChoice = "4"
    }
    
    switch ($mailChoice) {
        "1" {
            Write-Host "  📧 Mailtrap configuration" -ForegroundColor $Cyan
            Set-Config -Key "MAIL_DRIVER" -Value "smtp" -Description "Mail driver"
            
            $mailtrapHost = if ($Interactive) { Read-Host "  Mailtrap SMTP Host" } else { "smtp.mailtrap.io" }
            Set-Config -Key "MAIL_HOST" -Value $mailtrapHost
            
            $mailtrapPort = if ($Interactive) { Read-Host "  Mailtrap SMTP Port" } else { "465" }
            Set-Config -Key "MAIL_PORT" -Value $mailtrapPort
            
            $mailtrapUser = if ($Interactive) { Read-Host "  Mailtrap Username" } else { "" }
            if ($mailtrapUser) { Set-Config -Key "MAIL_USERNAME" -Value $mailtrapUser }
            
            $mailtrapPass = if ($Interactive) { Read-Host "  Mailtrap Password" } else { "" }
            if ($mailtrapPass) { Set-Config -Key "MAIL_PASSWORD" -Value $mailtrapPass }
            
            Set-Config -Key "MAIL_ENCRYPTION" -Value "tls" -Description "Mail encryption"
            Set-Config -Key "MAIL_FROM_ADDRESS" -Value "noreply@appdemoheroku3.herokuapp.com" -Description "From address"
        }
        "2" {
            Write-Host "  📧 SendGrid configuration" -ForegroundColor $Cyan
            Set-Config -Key "MAIL_DRIVER" -Value "sendgrid" -Description "Mail driver"
            
            $sendgridKey = if ($Interactive) { Read-Host "  SendGrid API Key" } else { "" }
            if ($sendgridKey) { Set-Config -Key "SENDGRID_API_KEY" -Value $sendgridKey }
        }
        default {
            Write-Host "  ℹ️  Mail configuration skipped" -ForegroundColor $Cyan
            Write-Host "  Configure later with: heroku config:set MAIL_DRIVER=... -a $AppName" -ForegroundColor $Gray
        }
    }
    
    # ADDITIONAL CONFIGURATION
    Write-Header "5. ADDITIONAL CONFIGURATION"
    
    # Logging
    Set-Config -Key "LOG_CHANNEL" -Value "single" -Description "Logging channel"
    Set-Config -Key "LOG_LEVEL" -Value "info" -Description "Logging level"
    
    # Timezone (optional but recommended)
    Set-Config -Key "APP_TIMEZONE" -Value "UTC" -Description "Application timezone"
    
    # Laravel specific
    Set-Config -Key "LOG_DEPRECATIONS_CHANNEL" -Value "null" -Description "Deprecation warnings"
    
    # FINAL SUMMARY
    Write-Header "CONFIGURATION COMPLETE"
    
    Write-Host "`n✅ Environment variables configured successfully!" -ForegroundColor $Green
    
    Write-Host "`nCurrent configuration:" -ForegroundColor $Cyan
    heroku config -a $AppName
    
    Write-Host "`nNext steps:" -ForegroundColor $Cyan
    Write-Host "  1. Deploy: .\deploy-heroku.ps1" -ForegroundColor $Yellow
    Write-Host "  2. Run migrations: heroku run 'php artisan migrate --force' -a $AppName" -ForegroundColor $Yellow
    Write-Host "  3. Check logs: heroku logs --tail -a $AppName" -ForegroundColor $Yellow
    
    Write-Host "`n💡 Commands for future configuration:" -ForegroundColor $Cyan
    Write-Host "  Set variable: heroku config:set KEY=VALUE -a $AppName" -ForegroundColor $Gray
    Write-Host "  View all: heroku config -a $AppName" -ForegroundColor $Gray
    Write-Host "  Remove var: heroku config:unset KEY -a $AppName" -ForegroundColor $Gray
    
}
catch {
    Write-Host "`n❌ Error: $_" -ForegroundColor $Red
    exit 1
}

Write-Host ""
