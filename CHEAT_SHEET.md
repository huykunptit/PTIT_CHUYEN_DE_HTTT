# 🎯 HEROKU DEPLOYMENT - ONE PAGE CHEAT SHEET

```
╔═══════════════════════════════════════════════════════════════════════════╗
║                    CINEMA BOOKING APP DEPLOYMENT                          ║
║                      Heroku Container Registry                            ║
║                      App: appdemoheroku3                                  ║
╚═══════════════════════════════════════════════════════════════════════════╝
```

---

## 🚀 **DEPLOY IN 3 COMMANDS**

```powershell
.\verify-heroku-setup.ps1              # Check prerequisites
.\setup-heroku-config.ps1              # Configure app
.\deploy-heroku.ps1 -WithMigration     # Deploy!
```

**Result:** App live at https://appdemoheroku3.herokuapp.com

---

## 📋 **BEFORE YOU START**

- [ ] Docker Desktop running: `docker ps` ✅
- [ ] Heroku logged in: `heroku auth:whoami` ✅
- [ ] App exists: `heroku apps` ✅
- [ ] In project dir: `d:\Cinemat` ✅

---

## 🔧 **KEY SCRIPTS**

| Script | Purpose | Command |
|--------|---------|---------|
| **verify-heroku-setup.ps1** | Check all prerequisites | `.\verify-heroku-setup.ps1` |
| **setup-heroku-config.ps1** | Configure environment | `.\setup-heroku-config.ps1` |
| **deploy-heroku.ps1** | Deploy to Heroku | `.\deploy-heroku.ps1 -WithMigration` |

---

## 🎯 **COMMON COMMANDS**

```powershell
# Verify setup
.\verify-heroku-setup.ps1

# Configure app
.\setup-heroku-config.ps1

# Deploy (first time with migrations)
.\deploy-heroku.ps1 -WithMigration

# Redeploy (after code changes)
.\deploy-heroku.ps1

# View logs (real-time)
heroku logs --tail -a appdemoheroku3

# Check app status
heroku ps -a appdemoheroku3

# Run database command
heroku run "php artisan migrate" -a appdemoheroku3

# Update config variable
heroku config:set APP_DEBUG=false -a appdemoheroku3

# View all config
heroku config -a appdemoheroku3

# Restart app
heroku restart -a appdemoheroku3
```

---

## ⚙️ **ENVIRONMENT VARIABLES**

**Required:**
```
APP_KEY=base64:xxxxx...    (from: php artisan key:generate --show)
APP_ENV=production
APP_DEBUG=false
APP_URL=https://appdemoheroku3.herokuapp.com
```

**Database (pick one):**
```
# Option 1: Heroku PostgreSQL
DATABASE_URL=postgresql://...   (auto-set after addon)

# Option 2: External MySQL
DATABASE_URL=mysql://user:pass@host/db
```

**Set with:**
```powershell
heroku config:set KEY=VALUE -a appdemoheroku3
```

---

## 🐳 **DOCKER IMAGE**

**Build:** 3-5 minutes (first time)  
**Size:** 300-500MB (optimized)  
**Subsequent:** 2-3 minutes

**Architecture:**
- Stage 1: Build image (install tools, build assets)
- Stage 2: Production image (copy built files, remove tools)
- Result: Smaller, faster, more secure

---

## 📖 **DOCUMENTATION**

| File | Purpose | Read Time |
|------|---------|-----------|
| **START_HERE.md** | Quick 5-step guide | 5 min ⭐ |
| **HEROKU_QUICK_REFERENCE.md** | Commands reference | 2 min ⚡ |
| **HEROKU_COMPLETE_SETUP.md** | Full walkthrough | 10 min 📚 |
| **HEROKU_DEPLOYMENT_GUIDE.md** | Detailed guide | 20 min 📖 |
| **FILE_INDEX.md** | File descriptions | 5 min 📑 |

---

## ✅ **DEPLOYMENT CHECKLIST**

### Before Deployment
- [ ] Read `START_HERE.md`
- [ ] Run `.\verify-heroku-setup.ps1` (all green)
- [ ] Run `.\setup-heroku-config.ps1`
- [ ] Check: `heroku config -a appdemoheroku3`

### During Deployment
- [ ] Run `.\deploy-heroku.ps1 -WithMigration`
- [ ] Wait 5-8 minutes
- [ ] Watch for ✅ success message

### After Deployment
- [ ] Check: `heroku logs -a appdemoheroku3`
- [ ] Visit: https://appdemoheroku3.herokuapp.com
- [ ] Verify: No error messages in logs
- [ ] Check: `heroku ps -a appdemoheroku3` shows `web.1 up`

---

## 🚨 **QUICK FIXES**

| Issue | Fix |
|-------|-----|
| "docker credentials not found" | `heroku container:logout && heroku container:login` |
| "App not found" | `heroku apps` (verify name is correct) |
| "Docker not running" | Start Docker Desktop |
| "500 error" | `heroku logs -a appdemoheroku3` (check logs) |
| "No web processes" | Check Procfile: `web: heroku-php-apache2 public/` |
| "Database error" | `heroku config -a appdemoheroku3 \| grep DATABASE` |

---

## 💡 **PRO TIPS**

1. **Always verify first**
   ```powershell
   .\verify-heroku-setup.ps1
   ```

2. **Use automated script**
   ```powershell
   .\deploy-heroku.ps1    # Better than manual
   ```

3. **Monitor after deploy**
   ```powershell
   heroku logs --tail -a appdemoheroku3
   ```

4. **Check logs first**
   - 80% of issues are in logs
   - `heroku logs -n 200 -a appdemoheroku3`

5. **Save important config**
   ```powershell
   heroku config -a appdemoheroku3 > my_config_backup.txt
   ```

---

## 📊 **WHAT HAPPENS**

```
1. You run deploy script
   ↓
2. Docker builds image (3-5 min)
   ├─ Stage 1: Installs everything
   └─ Stage 2: Optimizes for production
   ↓
3. Pushes to Heroku Registry (1-2 min)
   ↓
4. Releases/deploys app (30 sec)
   ├─ Stops old containers
   ├─ Starts new containers
   └─ Routes traffic
   ↓
5. App is LIVE! 🎉
   ↓
6. Script shows you the logs
```

---

## 🔗 **USEFUL LINKS**

- **App URL:** https://appdemoheroku3.herokuapp.com
- **Heroku Dashboard:** https://dashboard.heroku.com
- **Heroku Docs:** https://devcenter.heroku.com
- **Container Registry:** https://devcenter.heroku.com/articles/container-registry-and-runtime
- **Laravel on Heroku:** https://devcenter.heroku.com/articles/getting-started-with-laravel

---

## 📞 **NEED HELP?**

**Check these in order:**

1. **Read:** `START_HERE.md` (Quick 5-step guide)
2. **Run:** `.\verify-heroku-setup.ps1` (Find the issue)
3. **Search:** `HEROKU_DEPLOYMENT_GUIDE.md` (Troubleshooting section)
4. **Check:** Heroku logs `heroku logs -a appdemoheroku3`
5. **Visit:** https://devcenter.heroku.com

---

## 🎯 **QUICK START**

### Right Now
```powershell
cd d:\Cinemat
.\verify-heroku-setup.ps1
.\setup-heroku-config.ps1
.\deploy-heroku.ps1 -WithMigration
```

### Then
```powershell
heroku logs --tail -a appdemoheroku3
```

### Visit
```
https://appdemoheroku3.herokuapp.com
```

---

## 💾 **IMPORTANT LINKS TO FILES**

| File | Location |
|------|----------|
| Configuration | `.\setup-heroku-config.ps1` |
| Deployment | `.\deploy-heroku.ps1` |
| Verification | `.\verify-heroku-setup.ps1` |
| Quick Ref | `HEROKU_QUICK_REFERENCE.md` |
| Full Guide | `HEROKU_COMPLETE_SETUP.md` |
| Start Here | `START_HERE.md` |
| File Index | `FILE_INDEX.md` |

---

## ⏱️ **TIME ESTIMATES**

| Task | Time |
|------|------|
| Read START_HERE.md | 5 min |
| Run verify script | 2 min |
| Run setup script | 3 min |
| Deploy (first time) | 5-8 min |
| Deploy (subsequent) | 2-3 min |
| **Total (first time)** | **20-25 min** |
| **Total (updates)** | **5-10 min** |

---

## 🎓 **WHAT YOU LEARNED**

✅ How to deploy Docker apps to Heroku  
✅ How to use Container Registry  
✅ How to configure environment variables  
✅ How to monitor and troubleshoot  
✅ How to use automation scripts  

---

## 🏁 **YOU'RE READY!**

```
✅ Documentation: Complete
✅ Scripts: Created
✅ Docker image: Optimized
✅ Configuration: Ready
✅ Deployment: Automated
```

**Deploy now:**
```powershell
.\verify-heroku-setup.ps1
.\setup-heroku-config.ps1
.\deploy-heroku.ps1 -WithMigration
```

**App will be at:**
```
https://appdemoheroku3.herokuapp.com
```

---

**Happy Deploying! 🚀**

*Cinema Booking App - Heroku Container Registry*  
*December 2025*

