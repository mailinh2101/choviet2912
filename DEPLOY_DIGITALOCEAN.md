# Hướng Dẫn Deploy Lên DigitalOcean

Hướng dẫn chi tiết từng bước để deploy dự án ChoViet29 (PHP + Node.js WebSocket) lên DigitalOcean Droplet.

## 📋 Yêu Cầu Trước Khi Deploy

- [ ] Tài khoản DigitalOcean
- [ ] Domain name (ví dụ: choviet29.com)
- [ ] SSH key đã setup
- [ ] Code đã commit lên GitHub/GitLab

## 🌐 Bước 1: Tạo Droplet Trên DigitalOcean

1. Đăng nhập vào DigitalOcean
2. Tạo Droplet mới:
   - **Image**: Ubuntu 22.04 LTS
   - **Plan**: Basic (ít nhất 2GB RAM)
   - **Datacenter**: Singapore (gần Việt Nam)
   - **Authentication**: SSH Key (upload SSH key của bạn)
   - **Hostname**: choviet29-server

3. Sau khi tạo xong, lấy IP address của droplet

## 🔗 Bước 2: Cấu Hình DNS

1. Vào quản lý DNS của domain
2. Tạo A Record:
   - **Type**: A
   - **Name**: @ (hoặc www)
   - **Value**: IP của droplet
   - **TTL**: 300 (5 phút)

3. Chờ DNS propagate (5-30 phút)
4. Kiểm tra: `ping your-domain.com`

## 🔧 Bước 3: SSH Vào Server và Setup Môi Trường

### 3.1 Kết nối SSH

```bash
# Từ PowerShell trên Windows
ssh root@your-droplet-ip
```

### 3.2 Cập nhật hệ thống

```bash
sudo apt update
sudo apt upgrade -y
```

### 3.3 Cài đặt các packages cần thiết

```bash
# Cài đặt dependencies cơ bản
sudo apt install -y curl git build-essential ufw

# Cài đặt Nginx
sudo apt install -y nginx

# Cài đặt PHP 8.1 và các extensions
sudo apt install -y php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-intl

# Cài đặt MySQL
sudo apt install -y mysql-server

# Cài đặt Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Cài đặt PM2 (Process Manager cho Node.js)
sudo npm install -g pm2

# Cài đặt Certbot (Let's Encrypt SSL)
sudo apt install -y certbot python3-certbot-nginx
```

### 3.4 Kiểm tra version

```bash
node -v        # Phải >= 18.x
npm -v
php -v         # Phải >= 8.1
nginx -v
mysql --version
pm2 -v
```

### 3.5 Cấu hình Firewall

```bash
# Cho phép SSH, HTTP, HTTPS
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable

# Kiểm tra status
sudo ufw status
```

## 📂 Bước 4: Clone Project Và Cấu Hình

### 4.1 Tạo thư mục và clone code

```bash
# Tạo thư mục
sudo mkdir -p /var/www
cd /var/www

# Clone từ Git (thay YOUR_REPO_URL)
sudo git clone https://github.com/mailinh2101/choviet2912.git choviet2912
cd choviet2912

# Hoặc upload bằng SCP từ máy Windows:
# scp -r "D:\laragon\www\choviet2912" root@your-droplet-ip:/var/www/
```

### 4.2 Cài đặt dependencies

```bash
# Cài Node.js packages
npm install

# Cài Composer packages (nếu có)
# composer install --no-dev
```

### 4.3 Cấu hình Node.js WebSocket Server

```bash
# Copy config production
cd /var/www/choviet2912/config
sudo cp server_config.production.js server_config.js

# Chỉnh sửa config
sudo nano server_config.js
```

**Cập nhật các giá trị sau trong `server_config.js`:**

```javascript
module.exports = {
  hostname: '127.0.0.1',
  port: 80,
  basePath: '',
  wsPort: 3000,
  wsSecret: 'YOUR_RANDOM_SECRET_KEY', // Tạo bằng: node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
  projectRoot: '/var/www/choviet2912',
  chatPath: '/var/www/choviet2912/chat',
  allowedOrigins: [
    'https://your-domain.com',
    'https://www.your-domain.com'
  ]
};
```

### 4.4 Tạo thư mục chat và cấp quyền

```bash
sudo mkdir -p /var/www/choviet2912/chat
sudo mkdir -p /var/www/choviet2912/logs
sudo mkdir -p /var/www/choviet2912/img

# Cấp quyền
sudo chown -R www-data:www-data /var/www/choviet2912
sudo chmod -R 755 /var/www/choviet2912
sudo chmod -R 775 /var/www/choviet2912/chat
sudo chmod -R 775 /var/www/choviet2912/logs
sudo chmod -R 775 /var/www/choviet2912/img
```

## 🗄️ Bước 5: Setup Database MySQL

```bash
# Đăng nhập MySQL
sudo mysql

# Tạo database và user
CREATE DATABASE choviet29_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'choviet29_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON choviet29_db.* TO 'choviet29_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database
mysql -u choviet29_user -p choviet29_db < /var/www/choviet2912/data/choviet29.sql
```

**Cập nhật file kết nối database PHP:**

```bash
sudo nano /var/www/choviet2912/model/connectdb.php
```

Sửa thông tin kết nối:
```php
$servername = "localhost";
$username = "choviet29_user";
$password = "your_strong_password";
$dbname = "choviet29_db";
```

## 🚀 Bước 6: Khởi Động Node.js WebSocket Server với PM2

```bash
cd /var/www/choviet2912

# Start WebSocket server
pm2 start js/server.js --name choviet-ws

# Xem logs
pm2 logs choviet-ws

# Lưu cấu hình PM2
pm2 save

# Setup PM2 để tự khởi động khi reboot
pm2 startup systemd
# Chạy lệnh mà PM2 in ra (thường là: sudo env PATH=...)

# Kiểm tra status
pm2 status
```

## 🌐 Bước 7: Cấu Hình Nginx

### 7.1 Tạo file config Nginx

```bash
sudo nano /etc/nginx/sites-available/choviet2912
```

Paste nội dung từ file `nginx.conf.example` (đã có sẵn trong repo), **nhớ thay đổi**:
- `your-domain.com` → domain thật của bạn
- Kiểm tra socket PHP-FPM: `ls -la /run/php/`

### 7.2 Enable site và restart Nginx

```bash
# Tạo symbolic link
sudo ln -s /etc/nginx/sites-available/choviet2912 /etc/nginx/sites-enabled/

# Xóa default site (nếu có)
sudo rm /etc/nginx/sites-enabled/default

# Test config
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
sudo systemctl enable nginx

# Kiểm tra status
sudo systemctl status nginx
```

## 🔐 Bước 8: Cài Đặt SSL Certificate (Let's Encrypt)

```bash
# Chạy Certbot
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Chọn:
# - Nhập email
# - Đồng ý terms
# - Chọn "2" để redirect HTTP -> HTTPS

# Test auto-renewal
sudo certbot renew --dry-run
```

## 🔧 Bước 9: Cập Nhật Frontend WebSocket URLs

Cần thay đổi các URL WebSocket hardcoded trong frontend từ `ws://localhost:3000` sang dynamic URLs.

### Các file cần sửa:

1. **js/chat.js** - Sửa function `getWebSocketURL()`
2. **view/livestream_broadcast.php**
3. **view/livestream_viewer.php**
4. **view/livestream_detail.php**
5. **view/streamer_panel.php**

**Thay thế code cũ:**
```javascript
// ❌ Cũ
const ws = new WebSocket('ws://localhost:3000');
```

**Bằng code mới:**
```javascript
// ✅ Mới
function getWebSocketURL() {
  const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
  const host = window.location.host;
  return `${protocol}//${host}/ws/`;
}

const ws = new WebSocket(getWebSocketURL());
```

## ✅ Bước 10: Kiểm Tra và Troubleshooting

### 10.1 Kiểm tra PM2

```bash
pm2 status
pm2 logs choviet-ws --lines 50
```

### 10.2 Kiểm tra Nginx

```bash
sudo systemctl status nginx
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/choviet2912_access.log
```

### 10.3 Kiểm tra Node đang lắng nghe

```bash
sudo ss -tulpn | grep node
# Phải thấy: LISTEN on *:3000
```

### 10.4 Kiểm tra PHP-FPM

```bash
sudo systemctl status php8.1-fpm
```

### 10.5 Test WebSocket từ server

```bash
# Cài wscat nếu chưa có
sudo npm install -g wscat

# Test local
wscat -c ws://127.0.0.1:3000

# Test qua Nginx
wscat -c wss://your-domain.com/ws/
```

### 10.6 Test từ Browser

1. Mở website: `https://your-domain.com`
2. Mở Developer Console (F12)
3. Check WebSocket connection trong tab Network
4. Thử gửi tin nhắn chat

## 🔄 Bước 11: Deploy Updates (Lần Sau)

Khi có code mới, chỉ cần:

```bash
# SSH vào server
ssh root@your-droplet-ip

# Chạy script deploy
cd /var/www/choviet2912
chmod +x deploy.sh
./deploy.sh
```

Hoặc chạy từng lệnh:

```bash
cd /var/www/choviet2912
git pull origin main
npm install
sudo chown -R www-data:www-data .
pm2 reload choviet-ws
sudo systemctl reload nginx
```

## 🐛 Các Lỗi Thường Gặp

### 1. PM2 process crash

```bash
# Xem logs chi tiết
pm2 logs choviet-ws --lines 100

# Thường do: missing module, config sai path
# Fix: npm install, check server_config.js
```

### 2. Nginx 502 Bad Gateway

```bash
# Kiểm tra Node có chạy không
pm2 status

# Kiểm tra port 3000
sudo ss -tulpn | grep 3000

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log
```

### 3. WebSocket connection failed

- Check browser console for errors
- Verify Nginx config có proxy WebSocket đúng
- Check SSL certificate nếu dùng wss://

### 4. Permission denied khi tạo chat files

```bash
sudo chown -R www-data:www-data /var/www/choviet2912/chat
sudo chmod -R 775 /var/www/choviet2912/chat
```

### 5. Database connection error

- Kiểm tra MySQL đang chạy: `sudo systemctl status mysql`
- Verify credentials trong `model/connectdb.php`
- Test connection: `mysql -u choviet29_user -p`

## 📊 Monitoring và Maintenance

### Xem logs realtime

```bash
# PM2 logs
pm2 logs choviet-ws

# Nginx access logs
sudo tail -f /var/log/nginx/choviet2912_access.log

# Nginx error logs
sudo tail -f /var/log/nginx/error.log

# System logs
sudo journalctl -u nginx -f
```

### Restart services

```bash
# Restart Node server
pm2 restart choviet-ws

# Restart Nginx
sudo systemctl restart nginx

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm

# Restart MySQL
sudo systemctl restart mysql
```

### Backup Database

```bash
# Tạo backup
mysqldump -u choviet29_user -p choviet29_db > backup_$(date +%Y%m%d).sql

# Restore từ backup
mysql -u choviet29_user -p choviet29_db < backup_20250112.sql
```

## 🔐 Security Best Practices

1. **Đổi password MySQL thường xuyên**
2. **Cập nhật system packages định kỳ**: `sudo apt update && sudo apt upgrade`
3. **Monitor logs**: Check logs hàng ngày
4. **Backup database**: Backup tối thiểu 1 tuần/lần
5. **Firewall**: Chỉ mở port cần thiết (22, 80, 443)
6. **Fail2ban**: Cài đặt fail2ban để chống brute force SSH

```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## 📞 Hỗ Trợ

Nếu gặp vấn đề:
1. Check logs: `pm2 logs`, `nginx logs`
2. Google error messages
3. Stack Overflow
4. DigitalOcean Community

## 🎉 Hoàn Thành!

Chúc mừng! Website của bạn đã chạy trên DigitalOcean với:
- ✅ PHP website
- ✅ Node.js WebSocket server
- ✅ SSL/HTTPS
- ✅ Auto-restart with PM2
- ✅ Nginx reverse proxy

URL: `https://your-domain.com` 🚀
