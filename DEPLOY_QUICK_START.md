# 🚀 Hướng Dẫn Nhanh - Deploy Lên DigitalOcean

## 📦 Các File Config Đã Tạo

1. **`config/server_config.production.js`** - Config Node.js cho production
2. **`nginx.conf.example`** - Config Nginx với WebSocket proxy
3. **`deploy.sh`** - Script tự động deploy updates
4. **`.env.example`** - Template cho environment variables
5. **`generate-secret.sh`** - Script generate WebSocket secret key
6. **`DEPLOY_DIGITALOCEAN.md`** - Hướng dẫn chi tiết đầy đủ
7. **`DEPLOY_CHECKLIST.md`** - Checklist để theo dõi tiến độ deploy

## ⚡ Quick Start

### 1. Chuẩn Bị

- Tạo Droplet Ubuntu 22.04 trên DigitalOcean
- Point domain A record về IP droplet
- SSH vào server

### 2. Cài Đặt Môi Trường

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install các packages cần thiết
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl git build-essential ufw certbot python3-certbot-nginx

# Install PM2
sudo npm install -g pm2

# Setup firewall
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 3. Clone & Setup Project

```bash
# Clone project
cd /var/www
sudo git clone https://github.com/mailinh2101/choviet2912.git choviet2912
cd choviet2912

# Install dependencies
npm install

# Create directories
sudo mkdir -p chat logs img
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 chat logs img
```

### 4. Cấu Hình

```bash
# Copy production config
cd config
sudo cp server_config.production.js server_config.js

# Generate secret key
cd ..
bash generate-secret.sh

# Edit config (update domain, secret, paths)
sudo nano config/server_config.js
```

### 5. Setup Database

```bash
sudo mysql
```

```sql
CREATE DATABASE choviet29_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'choviet29_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON choviet29_db.* TO 'choviet29_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Import database
mysql -u choviet29_user -p choviet29_db < data/choviet29.sql

# Update PHP database config
sudo nano model/connectdb.php
```

### 6. Start Node Server

```bash
cd /var/www/choviet2912
pm2 start js/server.js --name choviet-ws
pm2 save
pm2 startup systemd
# Chạy lệnh mà PM2 in ra
```

### 7. Configure Nginx

```bash
# Tạo config file
sudo nano /etc/nginx/sites-available/choviet2912
# Copy nội dung từ nginx.conf.example, thay your-domain.com

# Enable site
sudo ln -s /etc/nginx/sites-available/choviet2912 /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default

# Test và restart
sudo nginx -t
sudo systemctl restart nginx
```

### 8. Setup SSL

```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 9. Verify

```bash
# Check services
pm2 status
sudo systemctl status nginx
sudo ss -tulpn | grep node

# Test WebSocket
sudo npm install -g wscat
wscat -c wss://your-domain.com/ws/
```

### 10. Test Website

- Mở `https://your-domain.com`
- Test login, chat, livestream
- Check Developer Console (F12) - không có lỗi

## 🔄 Deploy Updates Sau Này

```bash
cd /var/www/choviet2912
./deploy.sh
```

Hoặc manual:
```bash
git pull origin main
npm install
pm2 reload choviet-ws
sudo systemctl reload nginx
```

## 📚 Tài Liệu Chi Tiết

- **DEPLOY_DIGITALOCEAN.md** - Hướng dẫn từng bước chi tiết
- **DEPLOY_CHECKLIST.md** - Checklist để tick ✅ từng bước

## 🐛 Troubleshooting

### PM2 không start
```bash
pm2 logs choviet-ws --lines 100
# Check config paths và permissions
```

### Nginx 502 Bad Gateway
```bash
# Check Node có chạy không
pm2 status
sudo ss -tulpn | grep 3000
```

### WebSocket không kết nối
- Check browser console
- Verify Nginx config có proxy WebSocket headers
- Check SSL certificate

### Permission denied
```bash
sudo chown -R www-data:www-data /var/www/choviet2912
sudo chmod -R 775 /var/www/choviet2912/chat
```

## ✨ Tính Năng Đã Cải Thiện

✅ **WebSocket URLs tự động detect môi trường**
- Development: `ws://localhost:3000`
- Production: `wss://your-domain.com/ws/`

✅ **Files đã update:**
- `js/chat.js`
- `view/livestream_viewer.php`
- `view/livestream_broadcast.php`
- `view/streamer_panel.php`
- `view/livestream_detail.php`

✅ **Config files mới:**
- Production config template
- Nginx config với WebSocket proxy
- Deploy script tự động
- Environment variables template

## 🔐 Security Notes

- Đổi `wsSecret` trong `server_config.js`
- Đổi password database
- Không commit `.env` vào Git
- Enable fail2ban: `sudo apt install fail2ban`
- Regular security updates: `sudo apt update && sudo apt upgrade`

## 📞 Hỗ Trợ

Nếu gặp vấn đề:
1. Check logs: `pm2 logs`, `sudo tail -f /var/log/nginx/error.log`
2. Xem DEPLOY_DIGITALOCEAN.md phần "Các Lỗi Thường Gặp"
3. Google error messages
4. Stack Overflow

---

**Chúc bạn deploy thành công! 🎉**
