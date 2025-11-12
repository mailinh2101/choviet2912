# 📚 Hướng Dẫn Deploy Chợ Việt Lên DigitalOcean - Tóm Tắt

## 🎯 Mục Đích
Tài liệu này là **bản tóm tắt nhanh** để deploy "Chợ Việt" marketplace lên DigitalOcean.

---

## 📖 Các File Tài Liệu

| File | Mục Đích | Thời Gian |
|------|---------|----------|
| **README.md** | 📖 Giới thiệu dự án & quick links | 5 phút |
| **QUICK_START.md** | ⚡ Deploy nhanh trong 30 phút | 30 phút |
| **DEPLOYMENT_GUIDE_VI.md** | 📚 Hướng dẫn chi tiết (70+ trang) | 2+ giờ |
| **SOURCE_CODE_OVERVIEW_VI.md** | 🔍 Hiểu cấu trúc source code | 30 phút |
| **USEFUL_COMMANDS.md** | 🔧 Tham khảo lệnh Linux/MySQL | Khi cần |
| **setup_server.sh** | 🚀 Automated setup (Linux script) | Auto |
| **deploy.ps1** | 🚀 Deployment helper (PowerShell) | Manual |

---

## ⚡ Bắt Đầu Ngay (Dành Cho Người Vội)

### Bước 1: Chuẩn Bị
```
✓ Có DigitalOcean account
✓ Có domain name (hoặc dùng IP)
✓ Có Gmail để gửi email
✓ Có SSH key
```

### Bước 2: Tạo Droplet
```
1. Vào: cloud.digitalocean.com
2. Create → Droplets
3. Chọn: Ubuntu 20.04, 2GB/2CPU, Singapore region
4. Lưu IP address
```

### Bước 3: SSH & Run Script
```bash
ssh root@YOUR_DROPLET_IP
curl -O https://raw.githubusercontent.com/HoangAn2912/muabandocu/main/setup_server.sh
bash setup_server.sh yourdomain.com secure_password
```

### Bước 4: Cấu Hình
```bash
# Update database credentials
nano /var/www/choviet/model/mConnect.php

# Update email credentials
nano /var/www/choviet/config/email_config.php

# Import database
mysql -u choviet_user -p choviet29 < /var/www/choviet/data/choviet29.sql
```

### Bước 5: Setup SSL
```bash
certbot --apache -d yourdomain.com
```

### Bước 6: Test
```bash
curl https://yourdomain.com
# Hoặc mở browser: https://yourdomain.com
```

---

## 📋 Dự Án Overview

### Tên Dự Án
**Chợ Việt** - Marketplace C2C (Mua/Bán/Trao Đổi Hàng Hóa)

### Tech Stack
- **Backend**: PHP 8.0 + MySQL
- **Frontend**: Bootstrap + JavaScript
- **Real-time**: Node.js WebSocket
- **Payment**: VNPay
- **Email**: Gmail SMTP

### Repository
https://github.com/HoangAn2912/muabandocu

### Tính Năng Chính
- ✅ Browse & search products
- ✅ User accounts & profiles
- ✅ Buy/sell products
- ✅ Real-time chat
- ✅ Live shopping
- ✅ VNPay payment
- ✅ Reviews & ratings
- ✅ Admin panel
- ✅ Wallet & top-up

---

## 🗂️ Cấu Trúc Dự Án

```
choviet2912/
├── admin/              # Admin panel
├── api/               # REST APIs
├── chat/              # Chat data
├── config/            # Configurations
├── controller/        # Business logic
├── css/               # Stylesheets
├── data/              # Database schema
├── helpers/           # Utilities
├── js/                # JavaScript & Node server
├── model/             # Data models
├── view/              # HTML templates
├── loginlogout/       # Auth pages
├── index.php          # Homepage
├── admin.php          # Admin panel
├── checkout.php       # Checkout
├── my_orders.php      # Orders
└── [documentation]    # Guides
```

---

## 💾 Database

### Schema
```
Database: choviet29
User: choviet_user
Password: (your_secure_password)
```

### Main Tables
- users (người dùng)
- products (sản phẩm)
- categories (danh mục)
- orders (đơn hàng)
- chats (tin nhắn)
- reviews (đánh giá)
- livestreams (live stream)
- transactions (giao dịch)

---

## 🔐 Security

### Bảo Vệ
- ✅ CSRF token protection
- ✅ XSS prevention
- ✅ SQL injection prevention
- ✅ Password hashing
- ✅ Secure session handling
- ✅ HTTPS/SSL

### Credentials
- **Database**: model/mConnect.php
- **Email**: config/email_config.php
- **Server**: config/server_config.js
- **WebSocket**: port 3000

---

## 🚀 Quick Reference

### Deploy (30 phút)
```bash
# 1. SSH vào server
ssh root@YOUR_DROPLET_IP

# 2. Chạy setup script
bash setup_server.sh domain.com db_password

# 3. Cấu hình credentials
nano /var/www/choviet/model/mConnect.php
nano /var/www/choviet/config/email_config.php

# 4. Setup SSL
certbot --apache -d domain.com

# 5. Test
curl https://domain.com
```

### Monitoring
```bash
# Check services
systemctl status apache2
systemctl status mysql
pm2 list

# View logs
tail -f /var/log/apache2/choviet-error.log
pm2 logs choviet-websocket

# Restart services
systemctl restart apache2 && systemctl restart mysql
```

### Backup
```bash
# Database
mysqldump -u choviet_user -p choviet29 > backup.sql

# Files
tar -czf choviet_backup.tar.gz /var/www/choviet
```

---

## 📞 Lệnh Hữu Ích

### Apache
```bash
systemctl restart apache2
apache2ctl configtest
apache2ctl -S
tail -f /var/log/apache2/choviet-error.log
```

### MySQL
```bash
mysql -u choviet_user -p choviet29
SHOW TABLES;
SELECT COUNT(*) FROM users;
EXIT;
```

### Node.js/PM2
```bash
pm2 start js/server.js --name "choviet-websocket"
pm2 list
pm2 logs choviet-websocket
pm2 restart choviet-websocket
```

### Git
```bash
git status
git pull origin main
git add .
git commit -m "Update"
git push origin main
```

---

## ❌ Troubleshooting

### Database error?
```bash
systemctl restart mysql
mysql -u choviet_user -p -e "SELECT VERSION();"
```

### Website not loading?
```bash
apache2ctl -S
apache2ctl configtest
systemctl restart apache2
tail /var/log/apache2/choviet-error.log
```

### SSL error?
```bash
certbot certificates
certbot renew --force-renewal
```

### WebSocket not working?
```bash
pm2 list
pm2 logs choviet-websocket
lsof -i :3000
```

---

## 📊 Performance Tips

1. **Enable Caching**
   - Browser cache headers ✓
   - Image lazy loading ✓
   - GZIP compression ✓

2. **Database**
   - Index important columns
   - Optimize queries
   - Regular maintenance

3. **Server**
   - Monitor resources (top, htop)
   - Check disk space (df -h)
   - View memory (free -h)

4. **Backup**
   - Daily database backup
   - Weekly file backup
   - Test restore regularly

---

## 🔄 Maintenance Schedule

### Daily
```bash
tail -100 /var/log/apache2/choviet-error.log
pm2 list
```

### Weekly
```bash
apt update
df -h
free -h
```

### Monthly
```bash
apt upgrade -y
mysqldump -u choviet_user -p choviet29 > backup.sql
certbot renew --dry-run
```

### Quarterly
```bash
tar -czf backup_full.tar.gz /var/www/choviet
Review performance & security
```

---

## 📚 For More Details

| Topic | File |
|-------|------|
| 30-minute deploy | **QUICK_START.md** |
| Detailed guide | **DEPLOYMENT_GUIDE_VI.md** |
| Source code | **SOURCE_CODE_OVERVIEW_VI.md** |
| Commands | **USEFUL_COMMANDS.md** |

---

## 🎯 Next Steps

1. ✅ Read: QUICK_START.md (30 minutes)
2. ✅ Create DigitalOcean Droplet
3. ✅ Run setup_server.sh
4. ✅ Configure credentials
5. ✅ Setup SSL
6. ✅ Test website
7. ✅ Monitor & maintain

---

## 💡 Tips

- 📝 **Backup important** - Database + Files
- 🔒 **Use strong passwords** - Min 16 characters
- 🔐 **Enable SSH keys** - More secure than passwords
- 📊 **Monitor regularly** - Check logs daily
- 🔄 **Update packages** - Monthly: apt upgrade
- 🧪 **Test before deploy** - Test locally first
- 📞 **Document changes** - Keep notes of what you did

---

## 🔗 Useful Links

| Resource | URL |
|----------|-----|
| DigitalOcean | https://www.digitalocean.com |
| GitHub Repo | https://github.com/HoangAn2912/muabandocu |
| Apache Docs | https://httpd.apache.org/docs/ |
| PHP Docs | https://www.php.net/docs.php |
| MySQL Docs | https://dev.mysql.com/doc/ |
| Ubuntu Docs | https://ubuntu.com/server/docs |

---

## 🎉 Selesai!

Anda sekarang memiliki:
- ✅ Marketplace application deployed
- ✅ Database configured
- ✅ Email working
- ✅ SSL/HTTPS enabled
- ✅ Real-time chat operational
- ✅ Admin panel ready

---

## 📞 Support

- GitHub Issues: https://github.com/HoangAn2912/muabandocu/issues
- Check documentation files
- Review code comments
- Contact repository owner

---

**Created**: November 2024
**Version**: 1.0
**Status**: Production Ready

---

**🚀 Happy deploying! Good luck with your marketplace! 🎉**

_Last Updated: November 12, 2024_
