# HEROKU CONTAINER REGISTRY DEPLOYMENT GUIDE

**App Name:** `appdemoheroku3`

---

## **QUICK START (3 COMMANDS)**

```powershell
# 1. Verify everything is ready
.\verify-heroku-setup.ps1

# 2. Deploy (builds and pushes to Heroku)
.\deploy-heroku.ps1

# 3. View logs
heroku logs --tail -a appdemoheroku3
```

---

## **DETAILED STEPS**

### **Step 1: Prerequisites**

Before deploying, ensure you have:

1. **Heroku Account** - https://heroku.com
2. **Heroku CLI** - https://devcenter.heroku.com/articles/heroku-cli
3. **Docker Desktop** - https://docker.com/products/docker-desktop
4. **App created on Heroku** - `appdemoheroku3`

Verify installation:
```powershell
heroku --version       # Should show version
docker --version       # Should show version
docker ps              # Docker must be running
heroku auth:whoami     # You must be logged in
```

---

### **Step 2: Configure Environment Variables**

Set required environment variables on Heroku:

```powershell
# Generate APP_KEY if you don't have one
# On your local machine: php artisan key:generate --show
# Then set it on Heroku:
heroku config:set APP_KEY="base64:xxxxxxxxxxxx" -a appdemoheroku3
heroku config:set APP_ENV=production -a appdemoheroku3
heroku config:set APP_DEBUG=false -a appdemoheroku3

# If using database
heroku config:set DATABASE_URL="mysql://user:pass@host:port/db" -a appdemoheroku3

# If using Redis/Cache
heroku config:set CACHE_DRIVER=redis -a appdemoheroku3
heroku config:set REDIS_URL="redis://xxxx" -a appdemoheroku3

# View all config
heroku config -a appdemoheroku3
```

---

### **Step 3: Automatic Deployment**

Use the provided PowerShell script:

```powershell
# Simple deploy (no migrations)
.\deploy-heroku.ps1

# Deploy with database migrations
.\deploy-heroku.ps1 -WithMigration

# Deploy with migrations and seeds
.\deploy-heroku.ps1 -WithMigration -WithSeed

# Deploy with custom app name
.\deploy-heroku.ps1 -AppName appdemoheroku3 -WithMigration
```

The script automatically:
- ✅ Checks prerequisites
- ✅ Logins to Heroku Container Registry
- ✅ Builds Docker image
- ✅ Pushes to Heroku
- ✅ Releases the app
- ✅ Runs migrations (optional)
- ✅ Shows logs

---

### **Step 4: Manual Deployment (Alternative)**

If you prefer to deploy manually:

```powershell
# Step 1: Login to Heroku Container Registry
heroku container:login

# Step 2: Build and push Docker image
heroku container:push web -a appdemoheroku3

# Step 3: Release the container
heroku container:release web -a appdemoheroku3

# Step 4: Check if app is running
heroku logs -n 100 -a appdemoheroku3

# Step 5: Run migrations (if needed)
heroku run "php artisan migrate --force" -a appdemoheroku3
```

---

### **Step 5: Post-Deployment**

#### Verify Deployment

```powershell
# Check app status
heroku apps:info -a appdemoheroku3

# Check recent logs
heroku logs -n 50 -a appdemoheroku3

# Real-time logs
heroku logs --tail -a appdemoheroku3

# Check container registry
heroku container:ls -a appdemoheroku3
```

#### Run Database Operations

```powershell
# Run migrations
heroku run "php artisan migrate" -a appdemoheroku3

# Run seeders
heroku run "php artisan db:seed" -a appdemoheroku3

# Run artisan commands
heroku run "php artisan tinker" -a appdemoheroku3

# Access shell
heroku ps:exec -a appdemoheroku3
```

#### Monitor Application

```powershell
# View config variables
heroku config -a appdemoheroku3

# Update config variable
heroku config:set KEY=VALUE -a appdemoheroku3

# Remove config variable
heroku config:unset KEY -a appdemoheroku3

# Restart app
heroku restart -a appdemoheroku3

# View dyno information
heroku ps -a appdemoheroku3
```

---

## **TROUBLESHOOTING**

### ❌ "Error: docker credentials not found"

```powershell
heroku container:logout
heroku container:login
# Try again
```

### ❌ "Build failed"

Check the build logs:
```powershell
heroku container:push web -a appdemoheroku3 --verbose
```

Common issues:
- Missing dependencies in `composer.json`
- Invalid PHP extensions
- Node.js build errors
- Insufficient memory during build

### ❌ "App crashes after deploy"

```powershell
# Check logs
heroku logs -a appdemoheroku3

# Common issues:
# 1. Database not configured
heroku config -a appdemoheroku3 | grep DATABASE_URL

# 2. Missing APP_KEY
heroku config -a appdemoheroku3 | grep APP_KEY

# 3. Wrong permissions
heroku run "chmod -R 775 storage bootstrap/cache" -a appdemoheroku3
```

### ❌ "H14 - No web processes running"

This means your Procfile is wrong or web dyno didn't start.

Check Procfile:
```
web: heroku-php-apache2 public/
```

Restart:
```powershell
heroku restart -a appdemoheroku3
```

### ❌ "Permission denied" errors

```powershell
heroku run "chown -R www-data:www-data /app/storage /app/bootstrap/cache" -a appdemoheroku3
heroku run "chmod -R 775 /app/storage /app/bootstrap/cache" -a appdemoheroku3
```

### ❌ "Database connection error"

```powershell
# Check if DATABASE_URL is set
heroku config -a appdemoheroku3 | grep DATABASE_URL

# If not set, configure database
heroku addons:create heroku-postgresql:hobby-dev -a appdemoheroku3

# Verify migration
heroku run "php artisan migrate" -a appdemoheroku3
```

### ❌ "Timeout during build"

The Docker build may be timing out if:
- Large node_modules
- Slow npm install
- Large vendor directory

Solutions:
- Use `.dockerignore` to exclude unnecessary files
- Cache Docker layers properly
- Increase build timeout

---

## **DOCKERFILE EXPLAINED**

The `Dockerfile.heroku` uses a **multi-stage build**:

```dockerfile
# Stage 1: Builder
FROM php:8.2-fpm-alpine AS builder
# - Install all dependencies
# - Build assets
# - Install composer packages
# Result: Large image with build tools

# Stage 2: Production
FROM php:8.2-fpm-alpine
# - Copy only built files from Stage 1
# - Install only runtime dependencies
# Result: Smaller, faster image for production
```

**Benefits:**
- ✅ Smaller final image (faster deployment)
- ✅ No build tools in production (better security)
- ✅ Faster deployments after first build
- ✅ Smaller dyno size needed

---

## **ENVIRONMENT VARIABLES REFERENCE**

### Required
```
APP_NAME=Cinema Booking
APP_ENV=production
APP_KEY=base64:xxxx...
APP_DEBUG=false
APP_URL=https://appdemoheroku3.herokuapp.com
```

### Database
```
DATABASE_URL=mysql://user:password@host:3306/database
# OR individual variables:
DB_CONNECTION=mysql
DB_HOST=hostname
DB_PORT=3306
DB_DATABASE=cinema_db
DB_USERNAME=user
DB_PASSWORD=pass
```

### Cache & Session
```
CACHE_DRIVER=redis
SESSION_DRIVER=cookie
QUEUE_DRIVER=redis
REDIS_URL=redis://xxxx
```

### Mail (if using external service)
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
```

### Payment (VNPay, etc.)
```
SEPAY_API_KEY=xxxxx
VNPAY_MERCHANT_ID=xxxxx
```

---

## **USEFUL COMMANDS CHEAT SHEET**

| Command | Description |
|---------|-------------|
| `heroku login` | Login to Heroku |
| `heroku logout` | Logout from Heroku |
| `heroku apps` | List all apps |
| `heroku apps:info -a APP_NAME` | App information |
| `heroku config -a APP_NAME` | View environment variables |
| `heroku config:set KEY=VALUE -a APP_NAME` | Set environment variable |
| `heroku logs -a APP_NAME` | View app logs |
| `heroku logs --tail -a APP_NAME` | Stream logs in real-time |
| `heroku ps -a APP_NAME` | View running processes |
| `heroku restart -a APP_NAME` | Restart app |
| `heroku run "command" -a APP_NAME` | Run one-off command |
| `heroku container:login` | Login to Container Registry |
| `heroku container:push web -a APP_NAME` | Build and push Docker image |
| `heroku container:release web -a APP_NAME` | Release container |
| `heroku container:ls -a APP_NAME` | List containers |
| `heroku releases -a APP_NAME` | View release history |
| `heroku addons -a APP_NAME` | View add-ons |

---

## **DEPLOYMENT WORKFLOW DIAGRAM**

```
Local Development
        ↓
Run: docker build (test locally)
        ↓
Run: heroku container:login
        ↓
Run: heroku container:push web -a appdemoheroku3
(Builds Docker image in Heroku infrastructure)
        ↓
Run: heroku container:release web -a appdemoheroku3
(Starts containers with new image)
        ↓
App Available at: https://appdemoheroku3.herokuapp.com
        ↓
Monitor with: heroku logs --tail -a appdemoheroku3
```

---

## **MONITORING & MAINTENANCE**

### Daily Monitoring
```powershell
# Check app is running
heroku ps -a appdemoheroku3

# Check recent errors
heroku logs -a appdemoheroku3

# Check response time
heroku releases -a appdemoheroku3
```

### Weekly Maintenance
```powershell
# Clear cache
heroku run "php artisan cache:clear" -a appdemoheroku3

# Optimize autoloader
heroku run "php artisan optimize" -a appdemoheroku3

# Check database
heroku run "php artisan tinker" -a appdemoheroku3
```

### Monthly Updates
```powershell
# Update dependencies locally, then:
# 1. Push code or dockerfile changes
# 2. Redeploy:
heroku container:push web -a appdemoheroku3
heroku container:release web -a appdemoheroku3
```

---

## **SCALING & PERFORMANCE**

### Dyno Types
- `web: 1x` - 512MB RAM (free tier, no longer available)
- `web: 2x` - 1024MB RAM (recommended for Laravel)
- `web: 2x-L` - 2560MB RAM (for high traffic)

### Scaling Commands
```powershell
# View current dyno size
heroku ps -a appdemoheroku3

# Scale to specific dyno type
heroku ps:scale web=1:2x -a appdemoheroku3
```

### Database Performance
```powershell
# View database stats
heroku pg:info -a appdemoheroku3

# Backup database
heroku pg:backups:capture -a appdemoheroku3

# Restore from backup
heroku pg:backups:restore BACKUP_ID -a appdemoheroku3
```

---

## **CLEANUP & TEARDOWN**

### Remove App (if needed)
```powershell
heroku apps:destroy --app=appdemoheroku3 --confirm=appdemoheroku3
```

### Clean Up Local Docker
```powershell
# Remove image
docker rmi cinema-app:test

# Clean up unused images
docker image prune -a
```

---

## **ADDITIONAL RESOURCES**

- [Heroku Dev Center](https://devcenter.heroku.com/)
- [Container Registry Docs](https://devcenter.heroku.com/articles/container-registry-and-runtime)
- [Laravel on Heroku](https://devcenter.heroku.com/articles/getting-started-with-laravel)
- [Procfile Format](https://devcenter.heroku.com/articles/procfile)
- [Environment Variables](https://devcenter.heroku.com/articles/config-vars)

---

**Last Updated:** December 2025  
**Laravel Version:** 11+  
**PHP Version:** 8.2  
**Docker:** Alpine Linux

