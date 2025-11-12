# 🚀 Hướng Dẫn Deploy Chợ Việt Lên DigitalOcean

## 📋 Mục Lục
1. [Tổng Quan Dự Án](#tổng-quan-dự-án)
2. [Yêu Cầu Hệ Thống](#yêu-cầu-hệ-thống)
3. [Chuẩn Bị DigitalOcean](#chuẩn-bị-digitalocean)
4. [Cài Đặt và Deploy](#cài-đặt-và-deploy)
5. [Cấu Hình Database](#cấu-hình-database)
6. [Cấu Hình Email](#cấu-hình-email)
7. [Cấu Hình SSL/HTTPS](#cấu-hình-sslhttps)
8. [Kiểm Tra và Troubleshooting](#kiểm-tra-và-troubleshooting)

---

## 🎯 Tổng Quan Dự Án

### Tên Dự Án
**Chợ Việt** - Nền tảng mua bán và trao đổi hàng hóa (Marketplace/C2C)

### Công Nghệ Sử Dụng
- **Backend**: PHP 7.4+ (MVC Pattern)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap
- **Database**: MySQL/MariaDB
- **Real-time Chat**: Node.js + WebSocket
- **Email**: PHPMailer + SMTP (Gmail)
- **Payment**: VNPay Integration
- **API**: RESTful API (Livestream, Chat, Orders, Reviews)

### Repository GitHub
```
https://github.com/HoangAn2912/muabandocu.git
Branch: main
```

---

## ⚙️ Yêu Cầu Hệ Thống

### Server Requirements
- **OS**: Ubuntu 20.04 LTS hoặc mới hơn
- **Droplet Size**: Tối thiểu 1GB RAM, 1 CPU (khuyến nghị 2GB/2CPU cho production)
- **Storage**: 20GB SSD

### Software Requirements
- **PHP**: 7.4 hoặc 8.0+ với extensions:
  - `php-mysql` (MySQL driver)
  - `php-curl` (HTTP requests)
  - `php-gd` (Image processing)
  - `php-mbstring` (String encoding)
  - `php-zip` (Archive support)
  - `php-json` (JSON support)

- **Web Server**: Apache 2.4 hoặc Nginx
- **Database**: MySQL 5.7+ hoặc MariaDB 10.3+
- **Node.js**: 14+ (cho WebSocket server)
- **Git**: Latest version
- **Composer**: Latest version
- **npm**: Latest version

---

## 🌐 Chuẩn Bị DigitalOcean

### Bước 1: Tạo Droplet
1. Đăng nhập vào [DigitalOcean Console](https://cloud.digitalocean.com)
2. Click **Create** → **Droplets**
3. Chọn cấu hình:
   - **Image**: Ubuntu 20.04 x64
   - **Size**: 2GB/2CPU (khuyến nghị)
   - **Region**: Chọn region gần với người dùng (Singapore, Tokyo, hoặc HCM nếu có)
   - **Additional Options**: 
     - ✅ Enable Monitoring
     - ✅ Enable Backups
   - **SSH Key**: Upload hoặc tạo SSH key mới
   - **Hostname**: `choviet-prod` hoặc tên khác

4. Click **Create Droplet** và chờ hoàn thành

### Bước 2: Cấu Hình Firewall (Optional nhưng Recommended)
```
- HTTP (80)
- HTTPS (443)
- SSH (22) - Chỉ cho IP của bạn
- Custom (3000) - Cho WebSocket
```

### Bước 3: Setup DNS
Cấu hình domain bạn trỏ về IP của Droplet:
- Type: A Record
- Host: @ (root domain)
- Value: [IP của Droplet]
- TTL: 3600

---

## 🔧 Cài Đặt và Deploy

### Bước 1: Kết Nối SSH
```bash
ssh root@YOUR_DROPLET_IP
```

### Bước 2: Update System
```bash
apt update && apt upgrade -y
apt install -y curl wget git vim nano
```

### Bước 3: Cài Đặt Apache & PHP
```bash
# Cài Apache
apt install -y apache2

# Cài PHP 8.0 (hoặc 7.4)
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php
apt update
apt install -y php8.0 php8.0-mysql php8.0-curl php8.0-gd php8.0-mbstring \
    php8.0-zip php8.0-json php8.0-xml

# Enable Apache modules
a2enmod rewrite
a2enmod ssl
a2enmod headers
systemctl restart apache2
```

### Bước 4: Cài Đặt MySQL/MariaDB
```bash
apt install -y mysql-server

# Secure MySQL installation
mysql_secure_installation
# - Đặt root password
# - Remove anonymous users
# - Disable remote root login
# - Remove test database
```

### Bước 5: Cài Đặt Node.js & npm
```bash
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
apt install -y nodejs

# Verify installation
node --version
npm --version
```

### Bước 6: Cài Đặt Composer
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer --version
```

### Bước 7: Clone Repository
```bash
cd /var/www
git clone https://github.com/HoangAn2912/muabandocu.git choviet
cd choviet
git checkout main
```

### Bước 8: Cài Đặt Dependencies
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

### Bước 9: Cấu Hình Permissions
```bash
cd /var/www/choviet

# Set owner to www-data (Apache user)
chown -R www-data:www-data .

# Set permissions
chmod -R 755 .
chmod -R 775 logs
chmod -R 775 chat
chmod -R 775 vendor

# Ensure proper permissions for important directories
chmod 750 config
chmod 750 model
chmod 750 controller
```

---

## 💾 Cấu Hình Database

### Bước 1: Tạo Database & User
```bash
mysql -u root -p
```

Sau đó chạy trong MySQL shell:
```sql
-- Tạo database
CREATE DATABASE choviet29 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tạo user
CREATE USER 'choviet_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';

-- Cấp quyền
GRANT ALL PRIVILEGES ON choviet29.* TO 'choviet_user'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Verify
SHOW DATABASES;
SHOW USERS;
EXIT;
```

### Bước 2: Import Database
```bash
cd /var/www/choviet
mysql -u choviet_user -p choviet29 < data/choviet29.sql
```

Nếu có prompt nhập password, nhập password đã tạo ở bước 1.

### Bước 3: Cập Nhật Cấu Hình Connection

Chỉnh sửa file `model/mConnect.php`:

```php
<?php
class Connect {
    public function connect() {
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
        }

        // Production configuration
        $con = mysqli_connect(
            "localhost",              // Host
            "choviet_user",          // Username (thay đổi)
            "your_secure_password_here", // Password (thay đổi)
            "choviet29"              // Database name
        );

        if (!$con) {
            error_log("Database Connection Error: " . mysqli_connect_error());
            die("Lỗi kết nối cơ sở dữ liệu");
        } else {
            mysqli_query($con, "SET NAMES 'utf8mb4'");
            @mysqli_query($con, "SET time_zone = '+07:00'");
            return $con;
        }
    }
}
?>
```

---

## 📧 Cấu Hình Email

### Bước 1: Tạo Gmail App Password
1. Đăng nhập vào [Google Account](https://myaccount.google.com/)
2. Chọn **Security** → **App passwords**
3. Chọn Mail + Windows/Mac/Linux
4. Sao chép password được tạo (16 ký tự)

### Bước 2: Cập Nhật Cấu Hình Email

Chỉnh sửa file `config/email_config.php`:

```php
<?php
return [
    'host' => 'smtp.gmail.com',
    'username' => 'your_email@gmail.com',      // Email thật của bạn
    'password' => 'xxxx xxxx xxxx xxxx',       // App password từ Google
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'your_email@gmail.com',
    'from_name' => 'Chợ Việt'
];
?>
```

### Bước 3: Test Email (Optional)
Tạo file test `test_email.php`:
```php
<?php
include_once('helpers/EmailNotification.php');

$result = EmailNotification::sendWelcomeEmail('test@example.com', 'Test User');
echo $result ? "Email sent successfully" : "Failed to send email";
?>
```

Chạy: `php test_email.php`

---

## 🔒 Cấu Hình SSL/HTTPS

### Bước 1: Cài Đặt Let's Encrypt & Certbot
```bash
apt install -y certbot python3-certbot-apache
```

### Bước 2: Tạo SSL Certificate
```bash
certbot --apache -d yourdomain.com -d www.yourdomain.com
```

Certbot sẽ:
- Xác thực domain của bạn
- Tạo certificate
- Tự động cấu hình Apache
- Bật HTTPS redirect

### Bước 3: Auto-renew Certificate
```bash
certbot renew --dry-run
```

Certbot sẽ tự động renew trước 30 ngày khi hết hạn.

---

## 🌐 Cấu Hình Apache Virtual Host

### Bước 1: Tạo Virtual Host Configuration
```bash
nano /etc/apache2/sites-available/choviet.conf
```

Paste nội dung:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    ServerAdmin admin@yourdomain.com
    
    DocumentRoot /var/www/choviet
    
    <Directory /var/www/choviet>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Log files
    ErrorLog ${APACHE_LOG_DIR}/choviet-error.log
    CustomLog ${APACHE_LOG_DIR}/choviet-access.log combined
    
    # Enable compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
    </IfModule>
    
    # Browser caching
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType image/jpg "access plus 1 year"
        ExpiresByType image/gif "access plus 1 year"
        ExpiresByType image/png "access plus 1 year"
        ExpiresByType text/css "access plus 1 month"
        ExpiresByType text/javascript "access plus 1 month"
        ExpiresByType application/javascript "access plus 1 month"
    </IfModule>
</VirtualHost>
```

### Bước 2: Enable Virtual Host
```bash
a2ensite choviet.conf
a2dissite 000-default.conf
apache2ctl configtest  # Kiểm tra syntax
systemctl restart apache2
```

---

## 📡 Cấu Hình WebSocket Server (Node.js)

### Bước 1: Cập Nhật Cấu Hình Server
Chỉnh sửa `config/server_config.js`:

```javascript
module.exports = {
  hostname: 'localhost',
  port: 8080,
  basePath: '',
  wsPort: 3000,
  wsSecret: '',
  projectRoot: '/var/www/choviet',
  chatPath: '/var/www/choviet/chat'
};
```

### Bước 2: Tạo PM2 Service (Recommended)
```bash
# Cài PM2 globally
npm install -g pm2

# Chạy server bằng PM2
cd /var/www/choviet
pm2 start js/server.js --name "choviet-websocket"

# Lưu PM2 config để auto-restart
pm2 save
pm2 startup

# Verify
pm2 list
```

### Bước 3: Cấu Hình Nginx Proxy (Recommended)
Cài Nginx:
```bash
apt install -y nginx
```

Tạo proxy config `/etc/nginx/sites-available/websocket`:
```nginx
upstream websocket {
    server localhost:3000;
}

server {
    listen 80;
    server_name yourdomain.com;
    
    location /ws {
        proxy_pass http://websocket;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_read_timeout 86400;
    }
}
```

---

## ⚙️ Cấu Hình Bổ Sung

### .htaccess - URL Rewriting
File ``.htaccess`` đã được cấu hình cho:
- Rewrite URLs thân thiện: `/username` → `index.php?username=$1`
- Admin URLs: `/ad/action` → `admin.php?action`
- API routes

Đảm bảo mod_rewrite được enable:
```bash
a2enmod rewrite
systemctl restart apache2
```

### Tạo Thư Mục Logs & Temp
```bash
cd /var/www/choviet
mkdir -p logs temp
chmod 775 logs temp
touch logs/error.log
touch logs/access.log
chmod 666 logs/*.log
```

### Cấu Hình PHP
Tạo/Chỉnh sửa `/etc/php/8.0/apache2/php.ini`:
```ini
max_upload_size = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
display_errors = Off
log_errors = On
error_log = /var/log/php-errors.log
```

Restart Apache:
```bash
systemctl restart apache2
```

---

## 🧪 Kiểm Tra và Troubleshooting

### Bước 1: Kiểm Tra Apache Status
```bash
systemctl status apache2
apache2ctl -S  # Danh sách virtual hosts
tail -f /var/log/apache2/choviet-error.log  # Xem real-time errors
```

### Bước 2: Kiểm Tra MySQL Connection
```bash
mysql -u choviet_user -p choviet29
SHOW TABLES;
EXIT;
```

### Bước 3: Kiểm Tra File Permissions
```bash
cd /var/www/choviet
ls -la | head -20
stat logs
stat chat
```

### Bước 4: Kiểm Tra Ports
```bash
netstat -tlnp
# Hoặc
ss -tlnp

# Kiểm tra port Apache (80, 443)
lsof -i :80
lsof -i :443

# Kiểm tra port Node.js (3000)
lsof -i :3000
```

### Bước 5: Test Website
```bash
# Trực tiếp từ server
curl http://localhost
curl https://localhost

# Hoặc mở browser
https://yourdomain.com
```

### Bước 6: Kiểm Tra Database Connection
Tạo file `test_db.php`:
```php
<?php
include_once('model/mConnect.php');
$db = new Connect();
$conn = $db->connect();

if ($conn) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $row = mysqli_fetch_assoc($result);
    echo "Database connection OK. Users count: " . $row['count'];
} else {
    echo "Database connection failed!";
}
?>
```

Chạy: `php test_db.php`

---

## 🚨 Common Issues & Solutions

### Issue 1: "Permission denied" errors
```bash
# Fix
chown -R www-data:www-data /var/www/choviet
chmod -R 755 /var/www/choviet
chmod -R 775 /var/www/choviet/logs
chmod -R 775 /var/www/choviet/chat
```

### Issue 2: "Connection refused" (Database)
```bash
# Check MySQL running
systemctl status mysql

# Check MySQL listening
netstat -tlnp | grep mysql

# Restart MySQL
systemctl restart mysql
```

### Issue 3: "Module mod_rewrite not enabled"
```bash
a2enmod rewrite
systemctl restart apache2
```

### Issue 4: "SSL certificate error"
```bash
# Renew certificate
certbot renew --force-renewal

# Check certificate
certbot certificates
```

### Issue 5: "Port 3000 already in use"
```bash
# Find process using port 3000
lsof -i :3000

# Kill process
kill -9 <PID>

# Or change port in server_config.js
```

### Issue 6: "Memory limit exceeded"
```bash
# Increase in php.ini
sed -i 's/memory_limit = .*/memory_limit = 512M/' /etc/php/8.0/apache2/php.ini
systemctl restart apache2
```

---

## 📊 Monitoring & Maintenance

### Monitor Server Performance
```bash
# Real-time monitoring
top

# Disk usage
df -h

# Memory usage
free -h

# Process management
ps aux | grep php
ps aux | grep node
```

### Backup Database
```bash
# Manual backup
mysqldump -u choviet_user -p choviet29 > /backups/choviet29_$(date +%Y%m%d_%H%M%S).sql

# Automated backup (cron)
0 2 * * * mysqldump -u choviet_user -pYOUR_PASSWORD choviet29 > /backups/choviet29_$(date +\%Y\%m\%d).sql
```

### Log Rotation
```bash
# Create logrotate config
nano /etc/logrotate.d/choviet

# Add:
/var/www/choviet/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 0660 www-data www-data
    sharedscripts
}
```

---

## 🔐 Security Best Practices

1. **Change Default Ports**
   - Đổi SSH port từ 22 sang port khác
   - Giới hạn SSH access bằng firewall

2. **Secure Credentials**
   - Không commit credentials vào Git
   - Dùng environment variables cho sensitive data
   - Rotate passwords định kỳ

3. **Enable Firewall**
   ```bash
   ufw enable
   ufw allow 22/tcp
   ufw allow 80/tcp
   ufw allow 443/tcp
   ufw allow 3000/tcp
   ufw status
   ```

4. **Regular Updates**
   ```bash
   apt update && apt upgrade -y
   ```

5. **SQL Injection Prevention**
   - Dùng prepared statements
   - Validate & sanitize input (đã implement trong Security.php)

6. **XSS Prevention**
   - HTML escape output
   - CSP headers trong Apache config

---

## 📞 Support & Documentation

### Useful Commands
```bash
# Restart all services
systemctl restart apache2 mysql

# Check service status
systemctl status apache2
systemctl status mysql
systemctl status php8.0-fpm

# View logs in real-time
tail -f /var/log/apache2/choviet-error.log
tail -f /var/log/apache2/access.log
tail -f /var/log/mysql/error.log

# Deploy code updates
cd /var/www/choviet
git pull origin main
composer install
npm install
systemctl restart apache2
pm2 restart choviet-websocket
```

### Resources
- [DigitalOcean Docs](https://docs.digitalocean.com)
- [Apache Documentation](https://httpd.apache.org/docs/)
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Node.js Documentation](https://nodejs.org/en/docs/)

---

## 📝 Deployment Checklist

- [ ] Tạo Droplet trên DigitalOcean
- [ ] Cấu hình SSH key
- [ ] Update system packages
- [ ] Cài Apache + PHP + MySQL + Node.js
- [ ] Cài Composer & npm
- [ ] Clone repository
- [ ] Cài dependencies (composer install, npm install)
- [ ] Tạo database & user
- [ ] Import database schema
- [ ] Cấu hình email credentials
- [ ] Cấu hình database connection
- [ ] Cấu hình server config (Node.js)
- [ ] Setup Apache virtual host
- [ ] Enable SSL/HTTPS certificate
- [ ] Cấu hình firewall
- [ ] Test website
- [ ] Setup monitoring & backups
- [ ] Domain pointing DNS
- [ ] Test all features (Login, Upload, Chat, Payment, etc)

---

**Chúc bạn deploy thành công! 🎉**

Nếu có bất kỳ vấn đề nào, hãy kiểm tra logs và thử các troubleshooting steps ở trên.
