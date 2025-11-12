# ⚡ Quick Deploy - App Platform

## 🎯 Bạn Đang Ở Đây

URL hiện tại: **https://sea-lion-app-oa3de.ondigitalocean.app/**

Đây là **DigitalOcean App Platform** (PaaS) - khác với Droplet!

## 🚀 Deploy Ngay Trong 3 Bước

### 1️⃣ Commit & Push

```powershell
# PowerShell (Windows)
cd D:\laragon\www\choviet2912

# Deploy tự động
.\deploy-app-platform.ps1
```

Hoặc manual:
```powershell
git add .
git commit -m "Update app"
git push origin main
```

### 2️⃣ App Platform Tự Động Deploy

- App Platform tự động detect push
- Build & deploy trong 3-5 phút
- Check progress: https://cloud.digitalocean.com/apps

### 3️⃣ Verify

```
Website: https://sea-lion-app-oa3de.ondigitalocean.app/
WebSocket: wss://sea-lion-app-oa3de.ondigitalocean.app/ws/
```

## 📁 Files Đã Tạo Cho App Platform

✅ `.do/app.yaml` - App Platform specification  
✅ `start-php.sh` - PHP service start script  
✅ `start-node.sh` - Node service start script  
✅ `config/server_config.appplatform.js` - App Platform config  
✅ `deploy-app-platform.ps1` - Deploy script  
✅ `DEPLOY_APP_PLATFORM.md` - Full guide  

## ⚙️ Cần Setup Lần Đầu

### 1. Configure App Spec

**Option A: Via Dashboard**
1. Go to https://cloud.digitalocean.com/apps
2. Select your app
3. Settings → App Spec
4. Edit → Copy content from `.do/app.yaml`
5. Save

**Option B: Via doctl CLI**
```powershell
# Install doctl
# https://docs.digitalocean.com/reference/doctl/how-to/install/

# Update app
doctl apps update YOUR_APP_ID --spec .do/app.yaml
```

### 2. Set Environment Variables

Dashboard → App → Settings → Environment Variables

**For WebSocket Service:**
```
NODE_ENV=production
WS_PORT=3000
WS_SECRET=your_random_secret  # Generate: node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
```

**For Database:**
```
DB_HOST=${db.HOSTNAME}
DB_PORT=${db.PORT}
DB_USER=${db.USERNAME}
DB_PASSWORD=${db.PASSWORD}
DB_NAME=${db.DATABASE}
```

### 3. Import Database

```powershell
# Get database credentials from App Platform dashboard
# Database → Connection Details

mysql -h your-db-host -P 25060 -u doadmin -p --ssl-mode=REQUIRED choviet29_db < data/choviet29.sql
```

## 🐛 Quick Troubleshooting

### Build Failed?
```
→ Check Logs: App → Components → Build Logs
→ Verify package.json has all dependencies
→ Check Node version (use 18.x)
```

### Service Won't Start?
```
→ Check Runtime Logs
→ Verify PORT environment variable
→ Check database connection
```

### WebSocket Not Connecting?
```
→ Verify route: /ws in app.yaml
→ Check browser console for errors
→ Test: wscat -c wss://your-app.ondigitalocean.app/ws/
```

### Database Connection Error?
```
→ Verify DATABASE_URL or individual DB_* variables
→ Check SSL mode: --ssl-mode=REQUIRED
→ Test connection from local machine first
```

## 📊 Monitor Your App

### Logs
```
Dashboard → App → Runtime Logs
- Web service logs
- WebSocket service logs
- Error logs
```

### Metrics
```
Dashboard → App → Insights
- CPU/Memory usage
- Request rate
- Response time
```

### Alerts
```
Dashboard → App → Alerts
- Set up email/Slack notifications
- Alert on high CPU, memory, errors
```

## 🔄 Deploy Updates

### Auto-Deploy (Recommended)
```powershell
# Just commit and push - App Platform does the rest
git add .
git commit -m "Your changes"
git push origin main

# Or use the script:
.\deploy-app-platform.ps1
```

### Manual Trigger
```
Dashboard → App → Deploy → Deploy Now
```

## 💰 Pricing

Current setup (~$20-30/month):

```
Web Service (PHP):     $5/month (Basic)
WebSocket Service:     $5/month (Basic)
Database (MySQL):     $15/month (Basic)
------------------------------------------
Total:                ~$25/month
```

## 📚 Full Documentation

- **DEPLOY_APP_PLATFORM.md** - Complete guide
- **App Platform Docs** - https://docs.digitalocean.com/products/app-platform/

## 🆘 Need Help?

1. Check logs in dashboard
2. Read DEPLOY_APP_PLATFORM.md
3. DigitalOcean Community: https://www.digitalocean.com/community
4. Support ticket: https://cloud.digitalocean.com/support

---

## ⚡ TL;DR

```powershell
# Deploy trong 1 lệnh:
.\deploy-app-platform.ps1

# Hoặc:
git add . && git commit -m "Update" && git push origin main
```

**✅ Done! App Platform tự động deploy!**

Check status: https://cloud.digitalocean.com/apps  
Visit app: https://sea-lion-app-oa3de.ondigitalocean.app/
