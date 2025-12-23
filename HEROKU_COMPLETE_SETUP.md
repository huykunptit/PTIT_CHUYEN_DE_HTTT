# 📚 COMPLETE HEROKU DEPLOYMENT PACKAGE

**Created:** December 2025  
**App Name:** `appdemoheroku3`  
**Deployment Method:** Heroku Container Registry (Docker)  

---

## 📦 **FILES CREATED FOR YOU**

| File | Purpose | How to Use |
|------|---------|-----------|
| **HEROKU_QUICK_REFERENCE.md** | Quick start guide (copy-paste commands) | Start here! |
| **HEROKU_DEPLOYMENT_GUIDE.md** | Complete detailed guide (English) | Read for full context |
| **DEPLOY_HEROKU_CONTAINER_REGISTRY.md** | Detailed setup (Vietnamese) | Vietnamese documentation |
| **verify-heroku-setup.ps1** | Pre-flight checklist script | Run before deployment |
| **deploy-heroku.ps1** | Automated deployment script | Main deployment tool |
| **setup-heroku-config.ps1** | Configure environment variables | Configure app settings |
| **Dockerfile.heroku** | Optimized production Dockerfile | Uses multi-stage build |

---

## 🚀 **QUICK START (FIRST TIME)**

### Step 1: Install Prerequisites
```powershell
# Install Heroku CLI if not already installed
choco install heroku-cli  # Windows with Chocolatey

# OR download from: https://devcenter.heroku.com/articles/heroku-cli

# Start Docker Desktop (required)
# Download from: https://docker.com/products/docker-desktop
```

### Step 2: Verify Everything Works
```powershell
# Test installation
heroku --version
docker --version
docker ps

# Login to Heroku
heroku login
```

### Step 3: Configure Your App
```powershell
# Verify setup
.\verify-heroku-setup.ps1

# Configure environment variables (interactive)
.\setup-heroku-config.ps1 -Interactive

# OR set up with defaults (minimal config)
.\setup-heroku-config.ps1
```

### Step 4: Deploy
```powershell
# Deploy with automatic migrations (recommended)
.\deploy-heroku.ps1 -WithMigration

# OR deploy without migrations
.\deploy-heroku.ps1

# Monitor logs (separate terminal)
heroku logs --tail -a appdemoheroku3
```

---

## 📋 **STEP-BY-STEP DEPLOYMENT**

### 1️⃣ **Prerequisites Check**

```powershell
# These must all work:
heroku --version          # Show Heroku CLI version
docker --version          # Show Docker version
docker ps                 # Docker must be running
heroku auth:whoami        # You must be logged in
```

### 2️⃣ **Pre-Flight Verification**

```powershell
# Run this before deploying (checks 50+ things)
.\verify-heroku-setup.ps1

# This checks:
# ✅ Tools installed (Heroku CLI, Docker)
# ✅ Docker running
# ✅ Logged in to Heroku
# ✅ App exists on Heroku
# ✅ Required files present
# ✅ Environment variables set
# ✅ Docker build test (optional)
```

### 3️⃣ **Configure Application**

```powershell
# Interactive setup (recommended first time)
.\setup-heroku-config.ps1 -Interactive

# OR automated setup with defaults
.\setup-heroku-config.ps1

# This sets up:
# ✅ APP_NAME, APP_ENV, APP_DEBUG
# ✅ APP_KEY (application encryption key)
# ✅ APP_URL
# ✅ Database configuration
# ✅ Cache driver (Redis or in-memory)
# ✅ Session driver
# ✅ Mail configuration
# ✅ Logging setup
```

### 4️⃣ **Deploy Application**

```powershell
# Option A: Automated deployment (recommended)
.\deploy-heroku.ps1

# With database migrations
.\deploy-heroku.ps1 -WithMigration

# With migrations and seeders
.\deploy-heroku.ps1 -WithMigration -WithSeed

# Option B: Manual deployment (if preferred)
heroku container:login
heroku container:push web -a appdemoheroku3
heroku container:release web -a appdemoheroku3
```

### 5️⃣ **Verify Deployment**

```powershell
# Check if app is running
heroku ps -a appdemoheroku3

# View recent logs
heroku logs -n 50 -a appdemoheroku3

# Stream logs in real-time
heroku logs --tail -a appdemoheroku3

# Check app info
heroku apps:info -a appdemoheroku3
```

### 6️⃣ **Post-Deployment Setup** (if needed)

```powershell
# Run database migrations
heroku run "php artisan migrate --force" -a appdemoheroku3

# Seed database
heroku run "php artisan db:seed --force" -a appdemoheroku3

# Clear cache
heroku run "php artisan cache:clear" -a appdemoheroku3

# View your app
https://appdemoheroku3.herokuapp.com
```

---

## ⚙️ **CONFIGURATION DETAILS**

### Required Environment Variables

```
APP_NAME=Cinema Booking
APP_ENV=production
APP_KEY=base64:xxxxx...                    # Generate locally first!
APP_DEBUG=false
APP_URL=https://appdemoheroku3.herokuapp.com
```

### Database Configuration

Option 1: Using Heroku PostgreSQL
```powershell
heroku addons:create heroku-postgresql:hobby-dev -a appdemoheroku3
# Automatically sets DATABASE_URL
```

Option 2: External Database
```powershell
heroku config:set DATABASE_URL="mysql://user:pass@host:3306/db" -a appdemoheroku3
# OR set individual variables:
heroku config:set DB_CONNECTION=mysql -a appdemoheroku3
heroku config:set DB_HOST=hostname -a appdemoheroku3
heroku config:set DB_PORT=3306 -a appdemoheroku3
heroku config:set DB_DATABASE=cinema -a appdemoheroku3
heroku config:set DB_USERNAME=user -a appdemoheroku3
heroku config:set DB_PASSWORD=pass -a appdemoheroku3
```

### Cache Configuration

Option 1: With Redis (better for production)
```powershell
heroku addons:create heroku-redis:premium-0 -a appdemoheroku3
heroku config:set CACHE_DRIVER=redis -a appdemoheroku3
heroku config:set SESSION_DRIVER=cookie -a appdemoheroku3
heroku config:set QUEUE_DRIVER=redis -a appdemoheroku3
```

Option 2: Without Redis (simpler, slower)
```powershell
heroku config:set CACHE_DRIVER=array -a appdemoheroku3
heroku config:set SESSION_DRIVER=cookie -a appdemoheroku3
heroku config:set QUEUE_DRIVER=sync -a appdemoheroku3
```

---

## 🐳 **DOCKER ARCHITECTURE**

### Multi-Stage Build (Optimized)

```dockerfile
STAGE 1: Builder
├─ Install build tools (npm, composer, gcc, etc)
├─ Build assets (npm run build)
├─ Install PHP dependencies (composer install)
└─ Result: Large image (~2GB)

STAGE 2: Production
├─ Copy only built files from Stage 1
├─ Install only runtime dependencies
├─ Remove build tools (save space)
└─ Result: Small image (~300-500MB)
```

**Benefits:**
- ✅ Faster deployments (smaller image to push)
- ✅ Smaller dyno size needed (saves money)
- ✅ Better security (no build tools in production)
- ✅ Faster docker pulls

---

## 🎯 **COMMON DEPLOYMENT SCENARIOS**

### Scenario 1: First-Time Deployment
```powershell
.\verify-heroku-setup.ps1           # Check prerequisites
.\setup-heroku-config.ps1            # Configure app
.\deploy-heroku.ps1 -WithMigration   # Deploy with migrations
heroku logs --tail -a appdemoheroku3 # Monitor
```

### Scenario 2: Update Code and Redeploy
```powershell
# Make code changes locally
# Then:
.\deploy-heroku.ps1                  # Rebuild and deploy
heroku logs --tail -a appdemoheroku3 # Monitor
```

### Scenario 3: Update Environment Variable
```powershell
heroku config:set KEY=VALUE -a appdemoheroku3
# App automatically restarts
heroku logs --tail -a appdemoheroku3
```

### Scenario 4: Run Database Migrations After Deploy
```powershell
heroku run "php artisan migrate --force" -a appdemoheroku3
heroku run "php artisan db:seed --force" -a appdemoheroku3
```

### Scenario 5: Troubleshoot Deployment Issues
```powershell
# View detailed logs
heroku logs -n 100 -a appdemoheroku3

# Check app status
heroku ps -a appdemoheroku3

# Restart app
heroku restart -a appdemoheroku3

# Run artisan command to debug
heroku run "php artisan tinker" -a appdemoheroku3
```

---

## 🔧 **USEFUL COMMANDS REFERENCE**

### Deployment Commands
```powershell
heroku container:login                    # Login to Container Registry
heroku container:push web -a appdemoheroku3  # Build & push Docker image
heroku container:release web -a appdemoheroku3  # Release/deploy
heroku container:ls -a appdemoheroku3     # List deployed containers
```

### App Management
```powershell
heroku apps:info -a appdemoheroku3        # App information
heroku ps -a appdemoheroku3               # Running processes
heroku restart -a appdemoheroku3          # Restart app
heroku logs -a appdemoheroku3             # View logs
heroku logs --tail -a appdemoheroku3      # Real-time logs
```

### Configuration
```powershell
heroku config -a appdemoheroku3                    # View all config
heroku config:set KEY=VALUE -a appdemoheroku3     # Set variable
heroku config:unset KEY -a appdemoheroku3         # Remove variable
```

### Artisan Commands
```powershell
heroku run "php artisan migrate" -a appdemoheroku3
heroku run "php artisan db:seed" -a appdemoheroku3
heroku run "php artisan cache:clear" -a appdemoheroku3
heroku run "php artisan optimize" -a appdemoheroku3
heroku run "php artisan tinker" -a appdemoheroku3
```

### Add-ons
```powershell
heroku addons -a appdemoheroku3                    # List add-ons
heroku addons:create heroku-postgresql:hobby-dev -a appdemoheroku3
heroku addons:create heroku-redis:premium-0 -a appdemoheroku3
heroku addons:info heroku-postgresql -a appdemoheroku3
```

---

## 🚨 **TROUBLESHOOTING**

### Issue: "Error: docker credentials not found"
```powershell
heroku container:logout
heroku container:login
heroku container:push web -a appdemoheroku3
```

### Issue: "Build failed" or "Build timeout"
```powershell
# Check logs with verbose output
heroku container:push web -a appdemoheroku3 --verbose

# Solutions:
# 1. Reduce npm packages (remove unused dependencies)
# 2. Increase build timeout in Docker settings
# 3. Check Dockerfile syntax
# 4. Ensure all dependencies are in composer.json/package.json
```

### Issue: "H14 - No web processes running"
```powershell
# Check Procfile
cat Procfile
# Should contain: web: heroku-php-apache2 public/

# Restart
heroku restart -a appdemoheroku3

# Check logs
heroku logs -a appdemoheroku3
```

### Issue: "Application Error" (500 error)
```powershell
# Check logs
heroku logs -a appdemoheroku3

# Check if APP_KEY is set
heroku config -a appdemoheroku3 | grep APP_KEY

# Check database connection
heroku config -a appdemoheroku3 | grep DATABASE

# Run migrations if needed
heroku run "php artisan migrate --force" -a appdemoheroku3
```

### Issue: "Permission denied" errors
```powershell
heroku run "chown -R www-data:www-data /app/storage /app/bootstrap/cache" -a appdemoheroku3
heroku run "chmod -R 775 /app/storage /app/bootstrap/cache" -a appdemoheroku3
```

---

## 📊 **MONITORING & MAINTENANCE**

### Daily
```powershell
# Check app status
heroku ps -a appdemoheroku3

# Check for errors in logs
heroku logs -n 50 -a appdemoheroku3
```

### Weekly
```powershell
# Clear cache
heroku run "php artisan cache:clear" -a appdemoheroku3

# Optimize autoloader
heroku run "php artisan optimize" -a appdemoheroku3

# Check database
heroku run "php artisan db:seed:refresh" -a appdemoheroku3 # Refresh seeds
```

### Monthly
```powershell
# Update dependencies locally
composer update
npm update

# Push changes to Heroku
.\deploy-heroku.ps1
```

---

## 💰 **PRICING & COSTS**

### Free (No Cost)
- First 550 dyno hours/month
- 5 free add-ons
- Limited to small dyno (512MB)
- NOT available for new signups (discontinued)

### Paid
- **Basic Dyno** ($7/month) - 1GB RAM
- **Standard Dyno** ($25/month) - 2.5GB RAM
- **PostgreSQL** ($9+/month)
- **Redis** ($15+/month)

**Estimate for Cinema Booking App:**
- 1x Basic Dyno: $7/month
- PostgreSQL: $9/month
- Redis (optional): $15/month
- **Total: $16-31/month**

---

## 🔗 **IMPORTANT LINKS**

- **App URL:** https://appdemoheroku3.herokuapp.com
- **Heroku Dashboard:** https://dashboard.heroku.com
- **Heroku Dev Center:** https://devcenter.heroku.com
- **Container Registry Guide:** https://devcenter.heroku.com/articles/container-registry-and-runtime
- **Laravel on Heroku:** https://devcenter.heroku.com/articles/getting-started-with-laravel
- **Procfile Reference:** https://devcenter.heroku.com/articles/procfile
- **Config Vars:** https://devcenter.heroku.com/articles/config-vars

---

## ✅ **PRE-DEPLOYMENT CHECKLIST**

- [ ] Docker is installed and running
- [ ] Heroku CLI is installed and logged in
- [ ] App created on Heroku: `appdemoheroku3`
- [ ] `verify-heroku-setup.ps1` passes all checks
- [ ] Environment variables configured via `setup-heroku-config.ps1`
- [ ] `composer.json` and `package.json` have all dependencies
- [ ] `.env` or `.env.example` exists in project root
- [ ] `Procfile` exists and is correct
- [ ] `Dockerfile` exists
- [ ] No sensitive data in code (check `.env`)
- [ ] Database add-on created (if using Heroku PostgreSQL)
- [ ] Ready to deploy with `deploy-heroku.ps1`

---

## 🆘 **GETTING HELP**

1. **Check logs first:** `heroku logs --tail -a appdemoheroku3`
2. **Read guides:** 
   - Quick: `HEROKU_QUICK_REFERENCE.md`
   - Detailed: `HEROKU_DEPLOYMENT_GUIDE.md`
   - Vietnamese: `DEPLOY_HEROKU_CONTAINER_REGISTRY.md`
3. **Run verification:** `.\verify-heroku-setup.ps1`
4. **Visit official docs:** https://devcenter.heroku.com

---

## 📝 **NEXT STEPS**

1. Run `.\verify-heroku-setup.ps1` to check prerequisites
2. Run `.\setup-heroku-config.ps1` to configure app
3. Run `.\deploy-heroku.ps1 -WithMigration` to deploy
4. Visit https://appdemoheroku3.herokuapp.com

---

**🎉 Happy Deploying!**

*Created: December 2025*  
*Framework: Laravel 11+*  
*PHP Version: 8.2*  
*Docker: Alpine Linux*  

