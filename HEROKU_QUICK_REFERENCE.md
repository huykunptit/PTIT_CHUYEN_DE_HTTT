# 🚀 HEROKU DEPLOYMENT QUICK REFERENCE

**App Name:** `appdemoheroku3`  
**Deployment Method:** Container Registry (Docker)  
**Language:** PHP 8.2  
**Framework:** Laravel  

---

## **⚡ QUICK START (Copy & Paste)**

### Option 1: Automated (Recommended)

```powershell
# 1. Verify setup
.\verify-heroku-setup.ps1

# 2. Deploy (interactive guide)
.\deploy-heroku.ps1

# 3. Monitor logs
heroku logs --tail -a appdemoheroku3
```

### Option 2: Manual Commands

```powershell
# 1. Login to Container Registry
heroku container:login

# 2. Build and push Docker image
heroku container:push web -a appdemoheroku3

# 3. Release container
heroku container:release web -a appdemoheroku3

# 4. Check logs
heroku logs -a appdemoheroku3

# 5. Run migrations (optional)
heroku run "php artisan migrate --force" -a appdemoheroku3
```

---

## **📋 CHECKLIST BEFORE DEPLOYING**

- [ ] Docker is installed and running (`docker ps` works)
- [ ] Heroku CLI is installed (`heroku --version` works)
- [ ] You're logged in to Heroku (`heroku auth:whoami` shows your email)
- [ ] App exists on Heroku (`heroku apps` shows `appdemoheroku3`)
- [ ] Environment variables are set:
  - [ ] `APP_KEY` - Generate with: `php artisan key:generate --show`
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `DATABASE_URL` - (if using database)
- [ ] Procfile exists and contains: `web: heroku-php-apache2 public/`
- [ ] .env or .env.example exists in project root
- [ ] composer.json exists

**Run the verification script:**
```powershell
.\verify-heroku-setup.ps1
```

---

## **🔧 SET UP ENVIRONMENT VARIABLES**

```powershell
# Set APP_KEY (generate first with: php artisan key:generate --show)
heroku config:set APP_KEY="base64:your_key_here" -a appdemoheroku3

# Set environment
heroku config:set APP_ENV=production -a appdemoheroku3

# Disable debug mode
heroku config:set APP_DEBUG=false -a appdemoheroku3

# View all variables
heroku config -a appdemoheroku3
```

---

## **📊 FILES CREATED FOR YOU**

1. **HEROKU_DEPLOYMENT_GUIDE.md** - Complete deployment guide (English)
2. **DEPLOY_HEROKU_CONTAINER_REGISTRY.md** - Detailed setup (Vietnamese)
3. **deploy-heroku.ps1** - Automated deployment script
4. **verify-heroku-setup.ps1** - Pre-flight checklist script
5. **Dockerfile.heroku** - Optimized Heroku Dockerfile (multi-stage build)
6. **HEROKU_QUICK_REFERENCE.md** - This file

---

## **📱 COMMON TASKS**

### Deploy New Version
```powershell
.\deploy-heroku.ps1
```

### View Logs
```powershell
# Last 50 lines
heroku logs -n 50 -a appdemoheroku3

# Real-time logs
heroku logs --tail -a appdemoheroku3
```

### Run Artisan Commands
```powershell
# Migrations
heroku run "php artisan migrate --force" -a appdemoheroku3

# Seeds
heroku run "php artisan db:seed" -a appdemoheroku3

# Cache clear
heroku run "php artisan cache:clear" -a appdemoheroku3

# Tinker (interactive shell)
heroku run "php artisan tinker" -a appdemoheroku3
```

### Restart App
```powershell
heroku restart -a appdemoheroku3
```

### Update Environment Variable
```powershell
heroku config:set KEY=VALUE -a appdemoheroku3
```

### View App Information
```powershell
heroku apps:info -a appdemoheroku3
```

---

## **🔴 COMMON ISSUES & SOLUTIONS**

| Issue | Solution |
|-------|----------|
| "Error: docker credentials not found" | Run `heroku container:logout` then `heroku container:login` |
| "H14 - No web processes running" | Check Procfile syntax: `web: heroku-php-apache2 public/` |
| App crashes after deploy | Run `heroku logs -a appdemoheroku3` to see error |
| Database connection error | Check: `heroku config -a appdemoheroku3 \| grep DATABASE_URL` |
| Permission denied errors | Run: `heroku run "chmod -R 775 storage bootstrap/cache" -a appdemoheroku3` |
| Build takes too long | This is normal (3-5 minutes). Check progress in console. |

---

## **🔗 USEFUL LINKS**

- View App: https://appdemoheroku3.herokuapp.com
- Heroku Dashboard: https://dashboard.heroku.com
- Dev Center: https://devcenter.heroku.com
- Status Page: https://status.heroku.com

---

## **💾 DOCKER MULTI-STAGE BUILD**

The provided `Dockerfile.heroku` uses two stages:

```
Stage 1 (Builder): 
  - Install all tools (npm, composer, build-essential)
  - Build assets, install dependencies
  - Large image (~2GB)

Stage 2 (Production):
  - Copy only built files from Stage 1
  - Install only runtime dependencies
  - Small image (~300-500MB)
  
Result: Faster deployments! ⚡
```

---

## **📈 DEPLOYMENT FLOW**

```
1. Run script or login to Container Registry
            ↓
2. Docker builds image locally on Heroku infrastructure
            ↓
3. Image is pushed to Heroku's registry
            ↓
4. New containers start with the new image
            ↓
5. Old containers are stopped and removed
            ↓
6. Your app is live! 🎉
```

---

## **⏱️ ESTIMATED TIME**

- First deployment: 5-10 minutes (builds everything)
- Subsequent deployments: 3-5 minutes (uses cached layers)
- Post-deployment tasks: 1-2 minutes

---

## **🆘 NEED HELP?**

1. Check logs: `heroku logs --tail -a appdemoheroku3`
2. Read full guide: `HEROKU_DEPLOYMENT_GUIDE.md`
3. Run verification: `.\verify-heroku-setup.ps1`
4. Visit Heroku docs: https://devcenter.heroku.com

---

## **📞 SUPPORT COMMANDS**

```powershell
# Get app info
heroku apps:info -a appdemoheroku3

# Get all config
heroku config -a appdemoheroku3

# Check deployed containers
heroku container:ls -a appdemoheroku3

# View release history
heroku releases -a appdemoheroku3

# Check dyno hours usage
heroku account
```

---

**Happy Deploying! 🚀**

*Last Updated: December 2025*

