# 🚀 Deploy Lên DigitalOcean App Platform

## 📋 App Platform vs Droplet

### App Platform (PaaS - hiện tại của bạn)
- ✅ Tự động deploy từ Git
- ✅ Auto-scaling
- ✅ Tự động SSL/HTTPS
- ✅ Không cần quản lý server
- ✅ Database managed
- ❌ Đắt hơn Droplet
- ❌ Ít control hơn

### Droplet (IaaS - VPS truyền thống)
- ✅ Full control
- ✅ Rẻ hơn
- ✅ Linh hoạt hơn
- ❌ Phải setup manual
- ❌ Phải quản lý server

**Recommendation**: Nếu bạn đã có App Platform URL, tôi sẽ hướng dẫn deploy trên đó!

---

## 🎯 Deploy Lên App Platform - Từng Bước

### Bước 1: Chuẩn Bị Repository

#### 1.1 Commit các file config mới
```powershell
cd D:\laragon\www\choviet2912

# Add files
git add .do/app.yaml
git add start-php.sh
git add start-node.sh
git add config/server_config.appplatform.js

# Commit
git commit -m "Add App Platform config files"

# Push
git push origin main
```

#### 1.2 Verify files đã push
- Kiểm tra trên GitHub: https://github.com/mailinh2101/choviet2912

### Bước 2: Tạo App Trên App Platform

#### Option A: Từ GitHub (Recommended)

1. **Vào DigitalOcean Dashboard**
   - https://cloud.digitalocean.com/apps

2. **Create App từ GitHub**
   - Click "Create App"
   - Choose "GitHub" as source
   - Authorize DigitalOcean to access GitHub
   - Select repository: `mailinh2101/choviet2912`
   - Branch: `main`

3. **Import App Spec**
   - Click "Edit App Spec"
   - Copy nội dung từ `.do/app.yaml`
   - Paste vào editor
   - Click "Save"

#### Option B: Từ App Spec File

1. **Upload file `.do/app.yaml`** trong dashboard
2. App Platform sẽ tự động detect và configure

### Bước 3: Configure Components

App Platform sẽ tạo 3 components:

#### 3.1 Web Service (PHP)
```
Name: web
Type: Service
Port: 8080
Routes: / (root path)
Build Command: mkdir -p chat logs img; chmod 755 chat logs img
Run Command: php -S 0.0.0.0:8080 -t .
```

#### 3.2 WebSocket Service (Node.js)
```
Name: websocket
Type: Service
Port: 3000
Routes: /ws (WebSocket path)
Build Command: npm install --production
Run Command: node js/server.js
```

#### 3.3 Database (MySQL)
```
Name: choviet29-db
Engine: MySQL 8
Size: Basic (512MB RAM)
```

### Bước 4: Set Environment Variables

Vào **Settings → Environment Variables**, thêm:

#### For Web Service (PHP):
```
APP_ENV=production
CHAT_PATH=/workspace/chat
PROJECT_ROOT=/workspace
```

#### For WebSocket Service (Node.js):
```
NODE_ENV=production
WS_PORT=3000
WS_SECRET=your_random_secret_here  # Tạo bằng: node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
```

#### Database Variables (auto-generated):
```
DATABASE_URL=mysql://user:pass@host:port/dbname
DB_HOST=${db.HOSTNAME}
DB_PORT=${db.PORT}
DB_USER=${db.USERNAME}
DB_PASSWORD=${db.PASSWORD}
DB_NAME=${db.DATABASE}
```

### Bước 5: Configure Database

#### 5.1 Get Database Credentials
- Vào Database component
- Copy connection details

#### 5.2 Import Database
```powershell
# From local machine
mysql -h your-db-host -P 25060 -u doadmin -p --ssl-mode=REQUIRED choviet29_db < data/choviet29.sql
```

#### 5.3 Update PHP Database Config

**Tạo file `config/database.php`:**
```php
<?php
// App Platform database config - reads from environment
$db_url = getenv('DATABASE_URL');
if ($db_url) {
    $url = parse_url($db_url);
    $servername = $url['host'];
    $username = $url['user'];
    $password = $url['pass'];
    $dbname = ltrim($url['path'], '/');
    $port = $url['port'] ?? 3306;
} else {
    // Fallback to individual env vars
    $servername = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $dbname = getenv('DB_NAME') ?: 'choviet29_db';
    $port = getenv('DB_PORT') ?: 3306;
}
?>
```

**Update `model/connectdb.php`:**
```php
<?php
require_once __DIR__ . '/../config/database.php';

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

### Bước 6: Update WebSocket URLs

Các files đã được update tự động để detect App Platform:

**File `js/chat.js` cần thêm:**
```javascript
function getWebSocketURL() {
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const hostname = window.location.hostname;
    
    // Local development
    if (hostname === 'localhost' || hostname === '127.0.0.1') {
        return 'ws://localhost:3000';
    }
    
    // DigitalOcean App Platform
    if (hostname.includes('ondigitalocean.app')) {
        return `${protocol}//${hostname}/ws/`;
    }
    
    // Custom domain (production)
    return `${protocol}//${hostname}/ws/`;
}
```

### Bước 7: Deploy App

#### 7.1 Auto Deploy
- App Platform tự động deploy khi có commit mới
- Mỗi lần push code → auto redeploy

#### 7.2 Manual Deploy
- Vào App Dashboard
- Click "Deploy" → "Deploy Now"

#### 7.3 Monitor Deployment
```
View Logs:
- Web service logs
- WebSocket service logs
- Build logs
- Runtime logs
```

### Bước 8: Configure Custom Domain (Optional)

#### 8.1 Add Domain
- Settings → Domains
- Add domain: `choviet29.com`
- Add DNS records:
  ```
  Type: CNAME
  Name: @
  Value: sea-lion-app-oa3de.ondigitalocean.app
  ```

#### 8.2 SSL Certificate
- App Platform tự động provision Let's Encrypt SSL
- Chờ 5-10 phút

### Bước 9: Test & Verify

#### 9.1 Check Services
```
URL: https://sea-lion-app-oa3de.ondigitalocean.app
WebSocket: wss://sea-lion-app-oa3de.ondigitalocean.app/ws/
```

#### 9.2 Test WebSocket
```powershell
# Install wscat
npm install -g wscat

# Test connection
wscat -c wss://sea-lion-app-oa3de.ondigitalocean.app/ws/
```

#### 9.3 Browser Test
- Open: https://sea-lion-app-oa3de.ondigitalocean.app
- Check Developer Console (F12)
- Test chat, livestream features

### Bước 10: Monitor & Scale

#### 10.1 Logs
```
Dashboard → App → Components → View Logs
- Runtime logs
- Error logs
- Access logs
```

#### 10.2 Metrics
```
Dashboard → Insights
- CPU usage
- Memory usage
- Request rate
- Response time
```

#### 10.3 Scaling
```
Settings → Resources
- Increase instance size
- Add more instances
- Auto-scaling rules
```

---

## 🔧 Troubleshooting App Platform

### Issue 1: Build Failed
```bash
# Check build logs
# Common issues:
- Missing package.json dependencies
- Wrong Node version
- Missing PHP extensions

# Fix:
- Verify package.json has all deps
- Add buildpack config
```

### Issue 2: Service Won't Start
```bash
# Check runtime logs
# Common issues:
- Wrong PORT variable
- Database connection failed
- Missing environment variables

# Fix:
- Use process.env.PORT
- Verify DATABASE_URL
- Check env vars in Settings
```

### Issue 3: WebSocket Connection Failed
```bash
# Check:
- Route configuration (/ws path)
- CORS settings
- WebSocket upgrade headers

# Fix in app.yaml:
routes:
  - path: /ws
```

### Issue 4: Database Connection Error
```bash
# Check:
- DATABASE_URL format
- SSL requirements
- Firewall rules

# Fix:
mysql -h host -P port -u user -p --ssl-mode=REQUIRED database
```

### Issue 5: File Upload/Chat Files Not Persisting
```bash
# App Platform uses ephemeral storage
# Solution: Use Spaces (S3-compatible) for file storage

# Install AWS SDK:
npm install aws-sdk

# Configure Spaces:
- Create Space on DigitalOcean
- Update file upload logic to use Spaces
```

---

## 📊 App Platform Pricing

### Basic Tier (Current)
```
Basic - $5/month per service
- 512MB RAM
- 1 vCPU
- Auto-scaling: 1-3 instances

Database - $15/month
- 1GB RAM
- 10GB storage
- 25 connections
```

### Professional Tier
```
Professional - $12/month per service
- 1GB RAM
- 1 vCPU
- More instances

Database - $25/month
- 2GB RAM
- 25GB storage
```

---

## 🔄 CI/CD với App Platform

### Auto Deploy từ Git
```yaml
# File: .do/deploy.yaml
on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to App Platform
        run: |
          # App Platform auto-deploys on push
          echo "Deployment triggered"
```

### Manual Deploy Script
```powershell
# deploy-app-platform.ps1
git add .
git commit -m "Deploy update"
git push origin main

# Wait for auto-deploy
Write-Host "⏳ Waiting for App Platform to deploy..."
Start-Sleep -Seconds 30

# Check status
Write-Host "✅ Check deployment at:"
Write-Host "https://cloud.digitalocean.com/apps"
```

---

## 🎯 Next Steps

1. **✅ Commit config files** (đã có sẵn)
2. **✅ Push to GitHub**
3. **Configure App Platform** theo bước 2-4
4. **Import database** theo bước 5
5. **Test website** theo bước 9

---

## 📞 Support

- **App Platform Docs**: https://docs.digitalocean.com/products/app-platform/
- **Current App**: https://sea-lion-app-oa3de.ondigitalocean.app/
- **Dashboard**: https://cloud.digitalocean.com/apps

---

**🎉 App Platform đã sẵn sàng! Deploy ngay thôi!**
