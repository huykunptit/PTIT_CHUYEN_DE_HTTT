param(
    [Parameter(Mandatory = $true)] [string] $AppName,
    [string] $EnvPath = ".env",
    [string[]] $ExcludeKeys = @(
        "DB_HOST","DB_PORT","DB_DATABASE","DB_USERNAME","DB_PASSWORD",
        "REDIS_HOST","REDIS_PORT","REDIS_PASSWORD","APP_URL"
    )
)

function Require-HerokuCLI {
    try {
        $v = heroku --version 2>$null
        if (-not $v) { throw "Heroku CLI not found" }
    } catch {
        Write-Error "Heroku CLI is required. Install from https://devcenter.heroku.com/articles/heroku-cli" -ErrorAction Stop
    }
}

function Set-HerokuVar {
    param([string]$Key, [string]$Value)
    if ([string]::IsNullOrWhiteSpace($Key)) { return }
    heroku config:set "$Key=$Value" -a $AppName | Out-Null
}

function Unset-HerokuVar {
    param([string[]]$Keys)
    if ($Keys -and $Keys.Count -gt 0) {
        heroku config:unset @Keys -a $AppName | Out-Null
    }
}

Require-HerokuCLI

# 1) Push most vars from local .env (excluding Docker-only and local-only ones)
if (Test-Path $EnvPath) {
    Get-Content $EnvPath | ForEach-Object {
        $line = $_.Trim()
        if ([string]::IsNullOrWhiteSpace($line)) { return }
        if ($line.StartsWith('#')) { return }
        $eqIndex = $line.IndexOf('=')
        if ($eqIndex -lt 1) { return }
        $key = $line.Substring(0, $eqIndex).Trim()
        $val = $line.Substring($eqIndex + 1).Trim()
        # Strip surrounding quotes
        if ($val.StartsWith('"') -and $val.EndsWith('"')) { $val = $val.Trim('"') }
        if ($val.StartsWith("'") -and $val.EndsWith("'")) { $val = $val.Trim("'") }
        if ($ExcludeKeys -contains $key) { return }
        Set-HerokuVar -Key $key -Value $val
    }
}

# 2) Production-safe overrides
Set-HerokuVar APP_ENV "production"
Set-HerokuVar APP_DEBUG "false"
Set-HerokuVar APP_URL ("https://{0}.herokuapp.com" -f $AppName)
Set-HerokuVar DB_CONNECTION "mysql"
Set-HerokuVar REDIS_CLIENT "predis"
Set-HerokuVar QUEUE_CONNECTION "redis"
Set-HerokuVar MYSQL_ATTR_SSL_CA "/etc/ssl/certs/ca-certificates.crt"

# 3) Unset Docker-only DB/Redis vars that can break Heroku connectivity
Unset-HerokuVar @("DB_HOST","DB_PORT","DB_DATABASE","DB_USERNAME","DB_PASSWORD","REDIS_HOST","REDIS_PORT","REDIS_PASSWORD","MEMCACHED_HOST")

# 4) Map JawsDB/ClearDB URL to DATABASE_URL if present
$dbUrl = heroku config:get JAWSDB_URL -a $AppName 2>$null
if (-not [string]::IsNullOrWhiteSpace($dbUrl)) {
    Set-HerokuVar DATABASE_URL $dbUrl
} else {
    $dbUrl = heroku config:get CLEARDB_DATABASE_URL -a $AppName 2>$null
    if (-not [string]::IsNullOrWhiteSpace($dbUrl)) {
        Set-HerokuVar DATABASE_URL $dbUrl
    }
}

# 5) Map Heroku Redis URL to REDIS_URL if present
$redisUrl = heroku config:get REDIS_URL -a $AppName 2>$null
if ([string]::IsNullOrWhiteSpace($redisUrl)) {
    Write-Warning "REDIS_URL not found. Install Heroku Redis with: heroku addons:create heroku-redis:mini -a $AppName"
}

Write-Host "Done. Config Vars updated for app '$AppName'."