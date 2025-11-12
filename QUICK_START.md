# ⚡ Quick Start - Deploy Chợ Việt lên DigitalOcean

## 🎯 Mục Tiêu
Deploy "Chợ Việt" marketplace lên DigitalOcean chỉ trong **30 phút**.

## 📋 Prerequisites
- ✅ DigitalOcean account
- ✅ Domain name (hoặc dùng IP tạm thời)
- ✅ SSH key (hoặc password)
- ✅ Credentials (Email SMTP, Database password)

---

## ⏱️ Timeline: ~30 phút

| Bước | Thời Gian | Mô Tả |
|------|-----------|-------|
| 1 | 2 phút | Tạo Droplet |
| 2 | 1 phút | SSH vào server |
| 3 | 10 phút | Chạy setup script |
| 4 | 5 phút | Cấu hình credentials |
| 5 | 5 phút | Setup SSL certificate |
| 6 | 5 phút | Test & verify |
| 7 | 2 phút | Configure DNS |

---

## 🚀 Bước 1: Tạo DigitalOcean Droplet (2 phút)

### 1.1 Tạo Droplet
1. Đăng nhập: https://cloud.digitalocean.com
2. Click **"Create"** → **"Droplets"**
3. **Chọn OS**: Ubuntu 20.04 x64
4. **Chọn Size**: 2GB/2CPU ($12/month)
5. **Chọn Region**: Singapore / Tokyo / HCM (gần nhất)
6. **SSH Key**: Thêm hoặc tạo mới
7. **Hostname**: `choviet-prod` hoặc tên khác
8. Click **"Create Droplet"**

### 1.2 Lấy IP Address
```
Droplet tạo xong → Copy IP (ví dụ: 203.0.113.25)
```

---

## 📡 Bước 2: SSH vào Server (1 phút)

```bash
# Windows PowerShell / Linux / Mac
ssh root@YOUR_DROPLET_IP

# Nếu dùng SSH key
ssh -i ~/.ssh/id_rsa root@YOUR_DROPLET_IP

# Nếu dùng password, nhập password khi được hỏi
```

---

## ⚙️ Bước 3: Chạy Setup Script (10 phút)

### 3.1 Download & Run Script
```bash
# Chạy command này trên server
cd /tmp
curl -O https://raw.githubusercontent.com/HoangAn2912/muabandocu/main/setup_server.sh
chmod +x setup_server.sh

# Run script (thay domain và password)
bash setup_server.sh choviet.com your_secure_password_here
```

**Script sẽ:**
- ✅ Update system packages
- ✅ Cài Apache, PHP 8.0, MySQL
- ✅ Cài Node.js & npm
- ✅ Clone repository từ GitHub
- ✅ Cài PHP & Node dependencies
- ✅ Set file permissions
- ✅ Configure Apache virtual host

---

## 🔐 Bước 4: Cấu Hình Credentials (5 phút)

### 4.1 Cập Nhật Database Connection
```bash
# SSH vào server (nếu chưa có)
ssh root@YOUR_DROPLET_IP

# Chỉnh sửa database config
nano /var/www/choviet/model/mConnect.php
```

**Tìm và thay:**
```php
// BEFORE (tại dòng ~15)
$con = mysqli_connect("localhost", "admin", "123456", "choviet29");

// AFTER
$con = mysqli_connect("localhost", "choviet_user", "your_secure_password_here", "choviet29");
```

Nhấn: `Ctrl + O` → `Enter` → `Ctrl + X` để save

### 4.2 Cập Nhật Email Config
```bash
nano /var/www/choviet/config/email_config.php
```

**Thay thế:**
```php
<?php
return [
    'host' => 'smtp.gmail.com',
    'username' => 'your_gmail@gmail.com',      // Thay
    'password' => 'xxxx xxxx xxxx xxxx',       // Google App Password (16 ký tự)
    'port' => 587,
    'encryption' => 'tls',
    'from_email' => 'your_gmail@gmail.com',    // Thay
    'from_name' => 'Chợ Việt'
];
?>
```

**Cách lấy Google App Password:**
1. Vào: https://myaccount.google.com/apppasswords
2. Chọn: Mail + Device
3. Copy password được tạo (16 ký tự)

### 4.3 Import Database
```bash
# Chạy trên server
mysql -u choviet_user -p choviet29 < /var/www/choviet/data/choviet29.sql

# Nhập password: your_secure_password_here
```

---

## 🔒 Bước 5: Setup SSL Certificate (5 phút)

```bash
# Chạy trên server
certbot --apache -d yourdomain.com -d www.yourdomain.com

# Chọn:
# 1. Nhập email cho notifications
# 2. Chọn "2" để redirect HTTP → HTTPS
```

---

## 🧪 Bước 6: Test & Verify (5 phút)

### 6.1 Test Apache
```bash
# Chạy trên server
curl http://yourdomain.com
# Hoặc
curl https://yourdomain.com
```

### 6.2 Test Database
```bash
mysql -u choviet_user -p choviet29 -e "SELECT COUNT(*) as users FROM users;"
# Nhập password
```

### 6.3 Start WebSocket Server
```bash
cd /var/www/choviet
pm2 start js/server.js --name "choviet-websocket"
pm2 save

# Verify
pm2 list
```

### 6.4 Open Website
```
https://yourdomain.com
```

---

## 🌐 Bước 7: Configure DNS (2 phút)

**Nếu bạn có domain riêng:**

1. Vào control panel của domain registrar (Namecheap, GoDaddy, etc)
2. Tìm DNS settings
3. Tạo A record:
   - Type: A
   - Name/Host: @ (root) hoặc www
   - Value: YOUR_DROPLET_IP (ví dụ: 203.0.113.25)
   - TTL: 3600

**Chờ ~5-30 phút để DNS propagate**

---

## ✅ Verification Checklist

- [ ] Droplet tạo thành công
- [ ] SSH vào server được
- [ ] Setup script chạy xong không lỗi
- [ ] Database credentials cập nhật
- [ ] Email credentials cập nhật
- [ ] Database imported
- [ ] SSL certificate active
- [ ] Website truy cập được (https://domain)
- [ ] WebSocket server running (pm2 list)
- [ ] DNS pointing to IP

---

## 🔥 Quick Troubleshooting

### ❌ "Connection refused" - Database
```bash
# Check MySQL
systemctl status mysql

# Restart MySQL
systemctl restart mysql

# Test connection
mysql -u choviet_user -p -e "SELECT VERSION();"
```

### ❌ "404 Not Found" - Website
```bash
# Check Apache config
apache2ctl -S

# Check error log
tail -20 /var/log/apache2/choviet-error.log

# Restart Apache
systemctl restart apache2
```

### ❌ "SSL error" - HTTPS
```bash
# Check certificate
certbot certificates

# Renew certificate
certbot renew --force-renewal

# Check Apache SSL config
ls -la /etc/apache2/sites-enabled/
```

### ❌ "WebSocket not working"
```bash
# Check if running
pm2 list

# View logs
pm2 logs choviet-websocket

# Restart
pm2 restart choviet-websocket

# Check port
lsof -i :3000
```

---

## 📊 Monitor Your Server

### Real-time Monitoring
```bash
# SSH vào server
ssh root@YOUR_DROPLET_IP

# View Apache errors live
tail -f /var/log/apache2/choviet-error.log

# View WebSocket logs
pm2 logs choviet-websocket

# View system resources
top
```

### Daily Checks
```bash
# Check all services
systemctl status apache2
systemctl status mysql
pm2 list

# Check disk space
df -h

# Check memory
free -h
```

---

## 🔄 Regular Maintenance

### Weekly
```bash
# Check for updates
apt update

# View logs for errors
tail -100 /var/log/apache2/choviet-error.log
```

### Monthly
```bash
# Backup database
mysqldump -u choviet_user -p choviet29 > backup_$(date +%Y%m%d).sql

# Update packages
apt upgrade -y

# Renew SSL (automatic, but verify)
certbot renew --dry-run
```

### Quarterly
```bash
# Full system backup
tar -czf /backups/choviet_full_$(date +%Y%m%d).tar.gz /var/www/choviet

# Update to latest PHP
php -v
```

---

## 🎓 Next Steps

### Để học thêm:
1. 📖 **Đọc**: `DEPLOYMENT_GUIDE_VI.md` - Chi tiết đầy đủ
2. 🔧 **Tham khảo**: `USEFUL_COMMANDS.md` - Các lệnh hữu ích
3. 📚 **Hiểu**: `SOURCE_CODE_OVERVIEW_VI.md` - Cấu trúc source code

### Để phát triển:
1. Thêm feature mới
2. Tối ưu performance
3. Setup backup automation
4. Setup monitoring alerts
5. Setup CI/CD pipeline

---

## 💡 Tips

- **Backup thường xuyên**: Database + Files
- **Monitor logs**: Kiểm tra errors hàng ngày
- **Update packages**: Chạy `apt upgrade` hàng tháng
- **Test backups**: Đảm bảo restore được
- **Security**: Dùng strong passwords, SSH keys
- **Documentation**: Ghi chép config thay đổi

---

## 📞 Support

- **GitHub**: https://github.com/HoangAn2912/muabandocu
- **DigitalOcean Docs**: https://docs.digitalocean.com
- **Apache Docs**: https://httpd.apache.org
- **MySQL Docs**: https://dev.mysql.com

---

## 🎉 Selamat!

Bạn đã successfully deploy **Chợ Việt** lên DigitalOcean!

**Bước tiếp theo:**
1. Kiểm tra website
2. Test tất cả features (Login, Upload, Chat, Payment)
3. Invite users
4. Monitor performance
5. Optimize nếu cần

---

**⏱️ Nếu có bất kỳ vấn đề, xem USEFUL_COMMANDS.md hoặc DEPLOYMENT_GUIDE_VI.md**

**🚀 Happy deploying!**
