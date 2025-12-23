# 🚀 START HERE - DEPLOYMENT IN 5 STEPS

**App Name:** `appdemoheroku3`  
**Language:** Vietnamese/English  
**Estimated Time:** 10 minutes setup + 5-8 minutes deployment

---

## **STEP 1: Install Prerequisites (if needed)**

### ✅ Check if you have everything

```powershell
# Open PowerShell and run these commands:

# Check Heroku CLI
heroku --version

# Check Docker
docker --version

# Check if Docker is running
docker ps
```

### ❌ If any command fails, install:

| Tool | Windows | Link |
|------|---------|------|
| **Heroku CLI** | choco install heroku-cli | https://devcenter.heroku.com/articles/heroku-cli |
| **Docker Desktop** | Download installer | https://docker.com/products/docker-desktop |

---

## **STEP 2: Login to Heroku**

```powershell
heroku login
# Opens browser to login
# Returns to terminal when done
```

---

## **STEP 3: Verify Everything is Ready** ⚡

Run the verification script (checks 50+ things):

```powershell
cd d:\Cinemat
.\verify-heroku-setup.ps1
```

**This will tell you:**
- ✅ All tools are installed
- ✅ You're logged in
- ✅ App exists on Heroku
- ✅ All required files present
- ✅ Ready to configure

**If everything passes → Go to STEP 4**  
**If something fails → Follow the suggestions shown**

---

## **STEP 4: Configure Your App** ⚙️

Run the configuration script:

```powershell
.\setup-heroku-config.ps1
```

**This sets up:**
- ✅ App name and environment
- ✅ APP_KEY (encryption key)
- ✅ Database configuration
- ✅ Cache driver
- ✅ Mail configuration
- ✅ And more...

**When asked for APP_KEY:**
1. You may need to generate it first locally:
   ```powershell
   # On your local machine, in project directory
   php artisan key:generate --show
   # Copy the output (starts with "base64:")
   ```
2. Paste it when the script asks

---

## **STEP 5: Deploy to Heroku** 🚀

Run the deployment script:

```powershell
.\deploy-heroku.ps1 -WithMigration
```

**This will:**
1. ✅ Build your Docker image
2. ✅ Push to Heroku
3. ✅ Release your app
4. ✅ Run database migrations
5. ✅ Show you the logs

**Expected output:**
```
[1] Checking prerequisites...
    ✅ Docker running
    ✅ Heroku logged in
    ✅ App exists

[2] Logging into Container Registry...
    ✅ Successfully logged in

[3] Building and pushing Docker image...
    (This takes 3-5 minutes...)
    ✅ Docker image pushed

[4] Releasing container...
    ✅ Container released

[5] Fetching logs...
    [App startup logs...]
    
✅ DEPLOYMENT SUCCESSFUL!

Your app is now live at: https://appdemoheroku3.herokuapp.com
```

---

## **DONE! 🎉**

Your app should now be live at:  
**https://appdemoheroku3.herokuapp.com**

### Next: Monitor your app
```powershell
# Watch logs in real-time
heroku logs --tail -a appdemoheroku3

# Check if app is running
heroku ps -a appdemoheroku3

# View configuration
heroku config -a appdemoheroku3
```

---

## **⚡ Quick Reference**

### Common Commands

```powershell
# View logs
heroku logs -a appdemoheroku3                    # Last 50 lines
heroku logs --tail -a appdemoheroku3             # Real-time

# Restart app
heroku restart -a appdemoheroku3

# Run database command
heroku run "php artisan migrate" -a appdemoheroku3

# Update configuration
heroku config:set KEY=VALUE -a appdemoheroku3

# Redeploy (after code changes)
.\deploy-heroku.ps1
```

---

## **🆘 Something Went Wrong?**

### Error: "Docker daemon is not running"
- **Fix:** Start Docker Desktop (click icon in taskbar)

### Error: "docker credentials not found"
- **Fix:** Run `heroku container:logout` then `heroku container:login`

### Error: "App not found"
- **Fix:** Make sure `appdemoheroku3` is created on https://heroku.com

### App shows "Application Error"
- **Fix:** Check logs: `heroku logs -a appdemoheroku3`

### Database connection error
- **Fix:** Check config: `heroku config -a appdemoheroku3 | grep DATABASE`

### More help?
- Quick ref: Read `HEROKU_QUICK_REFERENCE.md`
- Full guide: Read `HEROKU_COMPLETE_SETUP.md`
- Vietnamese: Read `DEPLOY_HEROKU_CONTAINER_REGISTRY.md`

---

## **📚 Documentation Files**

All these files are in your project directory:

1. **README_HEROKU_DEPLOYMENT.md** ← Summary of everything
2. **HEROKU_QUICK_REFERENCE.md** ← Quick copy-paste commands
3. **HEROKU_COMPLETE_SETUP.md** ← Comprehensive guide
4. **HEROKU_DEPLOYMENT_GUIDE.md** ← Detailed walkthrough (English)
5. **DEPLOY_HEROKU_CONTAINER_REGISTRY.md** ← Vietnamese guide

---

## **🔍 Verify Deployment Success**

After deployment, check these things:

```powershell
# 1. App is running
heroku ps -a appdemoheroku3
# Should show: web.1 up

# 2. No errors in logs
heroku logs -a appdemoheroku3
# Should NOT show "Error" or "Exception"

# 3. Can access the app
# Visit: https://appdemoheroku3.herokuapp.com
# Should NOT show "Application Error"
```

---

## **💾 Updating Your App**

When you make changes locally:

```powershell
# 1. Make your code changes
# 2. Run deployment script
.\deploy-heroku.ps1

# 3. That's it! Your changes are live
```

---

## **📞 Need More Help?**

1. **Read the documentation** (you have 5 different guides!)
2. **Check logs** (most issues are visible there)
3. **Visit Heroku official docs:** https://devcenter.heroku.com

---

## **✨ What You Now Have**

```
✅ Heroku app ready (appdemoheroku3)
✅ Docker image optimized for production
✅ 5 PowerShell automation scripts
✅ Complete documentation (4 guides)
✅ Everything you need to deploy!
```

---

**Ready? Start with Step 1! 🚀**

---

*Cinema Booking App - Heroku Container Registry Deployment*  
*Created: December 2025*

