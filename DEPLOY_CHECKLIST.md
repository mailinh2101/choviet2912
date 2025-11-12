# ✅ Deploy Checklist - DigitalOcean

## 📝 Pre-Deployment

- [ ] Code đã commit và push lên GitHub/GitLab
- [ ] Database backup đã tạo (file .sql)
- [ ] Đã có tài khoản DigitalOcean
- [ ] Đã có domain name
- [ ] SSH key đã tạo trên máy local

## 🌊 DigitalOcean Setup

- [ ] Tạo Droplet Ubuntu 22.04 (tối thiểu 2GB RAM)
- [ ] Add SSH key vào Droplet
- [ ] Note IP address của Droplet: `_________________`
- [ ] Đặt tên droplet: `choviet29-server`

## 🔗 DNS Configuration

- [ ] Truy cập quản lý DNS của domain
- [ ] Tạo A Record: @ → IP Droplet
- [ ] Tạo A Record: www → IP Droplet
- [ ] Chờ DNS propagate (5-30 phút)
- [ ] Test: `ping your-domain.com`

## 🔧 Server Setup (SSH vào droplet)

### System Update
```bash
- [ ] sudo apt update
- [ ] sudo apt upgrade -y
```

### Install Dependencies
```bash
- [ ] sudo apt install -y curl git build-essential ufw nginx
- [ ] curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
- [ ] sudo apt install -y nodejs
- [ ] sudo npm install -g pm2
- [ ] sudo apt install -y php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl
- [ ] sudo apt install -y mysql-server
- [ ] sudo apt install -y certbot python3-certbot-nginx
```

### Verify Versions
```bash
- [ ] node -v (>= 18.x)
- [ ] npm -v
- [ ] php -v (>= 8.1)
- [ ] nginx -v
- [ ] mysql --version
- [ ] pm2 -v
```

### Firewall
```bash
- [ ] sudo ufw allow OpenSSH
- [ ] sudo ufw allow 'Nginx Full'
- [ ] sudo ufw enable
- [ ] sudo ufw status
```

## 📂 Project Deployment

### Clone Code
```bash
- [ ] cd /var/www
- [ ] sudo git clone https://github.com/mailinh2101/choviet2912.git choviet2912
- [ ] cd choviet2912
```

### Install Dependencies
```bash
- [ ] npm install
```

### Create Required Directories
```bash
- [ ] sudo mkdir -p chat logs img
- [ ] sudo chown -R www-data:www-data .
- [ ] sudo chmod -R 755 .
- [ ] sudo chmod -R 775 chat logs img
```

### Configure Node Server
```bash
- [ ] cd config
- [ ] sudo cp server_config.production.js server_config.js
- [ ] sudo nano server_config.js
```

**Cập nhật trong server_config.js:**
- [ ] hostname: `127.0.0.1`
- [ ] port: `80`
- [ ] basePath: `''`
- [ ] wsPort: `3000`
- [ ] wsSecret: Generate bằng: `bash generate-secret.sh`
- [ ] projectRoot: `/var/www/choviet2912`
- [ ] chatPath: `/var/www/choviet2912/chat`
- [ ] allowedOrigins: Update domain thật

## 🗄️ Database Setup

```bash
- [ ] sudo mysql
```

```sql
- [ ] CREATE DATABASE choviet29_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
- [ ] CREATE USER 'choviet29_user'@'localhost' IDENTIFIED BY 'strong_password_here';
- [ ] GRANT ALL PRIVILEGES ON choviet29_db.* TO 'choviet29_user'@'localhost';
- [ ] FLUSH PRIVILEGES;
- [ ] EXIT;
```

```bash
- [ ] mysql -u choviet29_user -p choviet29_db < data/choviet29.sql
```

### Update PHP Database Config
```bash
- [ ] sudo nano model/connectdb.php
```
Update:
- [ ] $servername = "localhost"
- [ ] $username = "choviet29_user"
- [ ] $password = "your_password"
- [ ] $dbname = "choviet29_db"

## 🚀 Start Node Server (PM2)

```bash
- [ ] cd /var/www/choviet2912
- [ ] pm2 start js/server.js --name choviet-ws
- [ ] pm2 logs choviet-ws (check for errors)
- [ ] pm2 save
- [ ] pm2 startup systemd
- [ ] Chạy lệnh mà PM2 in ra
- [ ] pm2 status
```

## 🌐 Nginx Configuration

```bash
- [ ] sudo nano /etc/nginx/sites-available/choviet2912
```
- [ ] Copy nội dung từ `nginx.conf.example`
- [ ] Thay `your-domain.com` bằng domain thật
- [ ] Check PHP-FPM socket: `ls -la /run/php/`
- [ ] Update socket path nếu cần (vd: php8.1-fpm.sock)

```bash
- [ ] sudo ln -s /etc/nginx/sites-available/choviet2912 /etc/nginx/sites-enabled/
- [ ] sudo rm /etc/nginx/sites-enabled/default
- [ ] sudo nginx -t
- [ ] sudo systemctl restart nginx
- [ ] sudo systemctl enable nginx
- [ ] sudo systemctl status nginx
```

## 🔐 SSL Certificate (Let's Encrypt)

```bash
- [ ] sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```
- [ ] Nhập email
- [ ] Đồng ý terms (Y)
- [ ] Chọn redirect HTTP → HTTPS (2)

```bash
- [ ] sudo certbot renew --dry-run
```

## ✅ Testing & Verification

### Check Services
```bash
- [ ] pm2 status (choviet-ws running)
- [ ] sudo systemctl status nginx (active)
- [ ] sudo systemctl status php8.1-fpm (active)
- [ ] sudo systemctl status mysql (active)
```

### Check Ports
```bash
- [ ] sudo ss -tulpn | grep 3000 (Node WebSocket)
- [ ] sudo ss -tulpn | grep nginx (80, 443)
```

### Check Logs
```bash
- [ ] pm2 logs choviet-ws --lines 50
- [ ] sudo tail -f /var/log/nginx/error.log
- [ ] sudo tail -f /var/log/nginx/choviet2912_access.log
```

### Test WebSocket
```bash
- [ ] sudo npm install -g wscat
- [ ] wscat -c ws://127.0.0.1:3000
- [ ] wscat -c wss://your-domain.com/ws/
```

### Browser Testing
- [ ] Mở `https://your-domain.com`
- [ ] Check HTTPS (padlock icon)
- [ ] Test login
- [ ] Test chat (gửi tin nhắn)
- [ ] Test livestream (nếu có)
- [ ] Check Developer Console (F12) - không có lỗi WebSocket

## 🎉 Post-Deployment

### Documentation
- [ ] Note lại credentials: database, domain, IP
- [ ] Lưu SSH key backup
- [ ] Document any custom configurations

### Security
- [ ] Đổi password MySQL
- [ ] Setup fail2ban: `sudo apt install -y fail2ban`
- [ ] Review firewall rules
- [ ] Enable automatic security updates

### Monitoring
- [ ] Setup monitoring (optional): UptimeRobot, Pingdom
- [ ] Schedule database backups
- [ ] Test auto-renewal SSL: Add calendar reminder

### Optional Enhancements
- [ ] Setup Redis for caching
- [ ] Configure CDN for static files
- [ ] Setup Git auto-deploy webhook
- [ ] Configure log rotation

## 🔄 Future Updates

Khi có code mới:
```bash
- [ ] SSH vào server
- [ ] cd /var/www/choviet2912
- [ ] ./deploy.sh
```

Hoặc manual:
```bash
- [ ] git pull origin main
- [ ] npm install
- [ ] pm2 reload choviet-ws
- [ ] sudo systemctl reload nginx
```

## 📞 Emergency Contacts

- **Droplet IP**: _________________
- **Domain**: _________________
- **Database Name**: choviet29_db
- **Database User**: choviet29_user
- **PM2 Process Name**: choviet-ws

## 📝 Notes

_Thêm ghi chú của bạn ở đây..._
