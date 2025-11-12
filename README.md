# 📖 README - Chợ Việt Marketplace

**Chợ Việt** là một nền tảng marketplace hiện đại cho phép người dùng mua, bán, trao đổi hàng hóa và tham gia live shopping.

---

## 📋 Thông Tin Dự Án

### Tên Dự Án
**Chợ Việt** - Marketplace C2C (Consumer to Consumer)

### Repository
- **GitHub**: https://github.com/HoangAn2912/muabandocu
- **Branch**: main
- **License**: MIT (hoặc tùy ý)

### Công Nghệ Sử Dụng
- **Backend**: PHP 7.4+, MySQL, REST API
- **Frontend**: HTML5, CSS3, Bootstrap, JavaScript
- **Real-time**: Node.js, WebSocket
- **Payment**: VNPay
- **Email**: PHPMailer + Gmail SMTP
- **Process Manager**: PM2

### Tác Giả
- **Name**: HoangAn2912
- **GitHub**: https://github.com/HoangAn2912

---

## 🌟 Tính Năng Chính

### 👥 Người Dùng (Users)
- ✅ Đăng ký, đăng nhập an toàn (CSRF, XSS protection)
- ✅ Quản lý tài khoản cá nhân
- ✅ Avatar, thông tin liên lạc
- ✅ Xem lịch sử giao dịch
- ✅ Ví điện tử với nạp tiền

### 🛍️ Mua/Bán Sản Phẩm (Marketplace)
- ✅ Duyệt sản phẩm theo danh mục
- ✅ Tìm kiếm và lọc nâng cao
- ✅ Xem chi tiết sản phẩm
- ✅ Đánh giá & bình luận
- ✅ Giỏ hàng & checkout
- ✅ Thanh toán VNPay

### 💬 Chat & Giao Tiếp (Real-time)
- ✅ Nhắn tin trực tiếp giữa buyer & seller
- ✅ Real-time WebSocket
- ✅ Chia sẻ file trong chat
- ✅ Đồng bộ trên nhiều devices
- ✅ Lưu lịch sử tin nhắn

### 📺 Live Shopping (Live Stream)
- ✅ Tạo live stream bán hàng
- ✅ Xem số lượng viewers
- ✅ Package management
- ✅ Interactive selling

### 👨‍💼 Admin Panel
- ✅ Quản lý users
- ✅ Quản lý sản phẩm & danh mục
- ✅ Xem doanh thu & thống kê
- ✅ Duyệt nạp tiền
- ✅ Quản lý giao dịch

---

## 📁 Cấu Trúc Dự Án

```
choviet2912/
├── admin/                 # Admin panel (React/Node)
├── api/                  # REST APIs
├── chat/                 # Chat data storage
├── config/               # Configuration files
├── controller/           # Business logic (20+ controllers)
├── css/                  # Stylesheets
├── data/                 # Database schema (choviet29.sql)
├── helpers/              # Utilities & security
├── img/                  # Images & assets
├── js/                   # JavaScript & Node.js server
├── lib/                  # Third-party libraries
├── loginlogout/          # Authentication pages
├── logs/                 # Application logs
├── model/                # Data models (15+ models)
├── scss/                 # SCSS source files
├── view/                 # HTML templates
├── vendor/               # PHP composer packages
├── .htaccess             # Apache rewrite rules
├── index.php             # Homepage
├── admin.php             # Admin panel
├── checkout.php          # Checkout page
├── my_orders.php         # Orders page
├── composer.json         # PHP dependencies
├── package.json          # Node.js dependencies
└── [deployment docs]     # Hướng dẫn deploy
```

**Chi tiết**: Xem `SOURCE_CODE_OVERVIEW_VI.md`

---

## 🗄️ Database

### Database Name
`choviet29`

### Main Tables
- **users** - Người dùng (buyers, sellers, admins)
- **products** - Sản phẩm
- **categories** - Danh mục
- **orders** - Đơn hàng
- **chats** - Tin nhắn
- **reviews** - Đánh giá
- **livestreams** - Live streams
- **wallets** - Ví điện tử
- **transactions** - Giao dịch

### Credentials (Local)
```
Username: admin
Password: 123456
Database: choviet29
```

---

## 🚀 Deployment

### ⚡ Quick Start (30 phút)
**Đọc**: `QUICK_START.md`

**Tóm tắt:**
```bash
# 1. Tạo Droplet trên DigitalOcean
# 2. SSH vào server
ssh root@YOUR_DROPLET_IP

# 3. Chạy setup script
bash setup_server.sh yourdomain.com db_password

# 4. Cấu hình credentials
# 5. Setup SSL
# 6. Test website
```

### 📖 Chi Tiết Đầy Đủ
**Đọc**: `DEPLOYMENT_GUIDE_VI.md` (70+ trang)

Bao gồm:
- Prerequisites & requirements
- Step-by-step setup
- Database configuration
- Email configuration
- SSL/HTTPS setup
- WebSocket server
- Monitoring & maintenance
- Security best practices
- Troubleshooting

### 🔧 Lệnh Hữu Ích
**Xem**: `USEFUL_COMMANDS.md`

Bao gồm:
- SSH commands
- Apache commands
- MySQL commands
- Node.js commands
- Git commands
- File permissions
- Monitoring & logs
- Backup & restore
- Firewall configuration

---

## 🛠️ Local Development

### Prerequisites
- XAMPP (Apache, PHP, MySQL)
- Git
- Composer
- Node.js & npm

### Setup (Windows XAMPP)

```bash
# 1. Clone repository
git clone https://github.com/HoangAn2912/muabandocu.git
cd choviet2912

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Import database
# - Open phpMyAdmin: http://localhost/phpmyadmin
# - Create database: choviet29
# - Import: data/choviet29.sql

# 5. Start WebSocket server
node js/server.js

# 6. Start Apache in XAMPP
# Or run: start_all.bat

# 7. Open browser
http://localhost/choviet2912
```

---

## 📦 Dependencies

### PHP (via Composer)
```json
{
    "phpmailer/phpmailer": "^6.10"
}
```

**Install:**
```bash
composer install
```

### Node.js (via npm)
```json
{
    "ws": "^8.18.2"
}
```

**Install:**
```bash
npm install
```

### Frontend Libraries
- Bootstrap 4+
- Font Awesome 5
- Owl Carousel
- Animate.css

---

## 🔐 Security Features

### Implemented
- ✅ **CSRF Protection** - CSRF token validation
- ✅ **XSS Prevention** - HTML entity encoding
- ✅ **SQL Injection Prevention** - Input validation & prepared statements
- ✅ **Session Security** - Secure session handling
- ✅ **Password Hashing** - bcrypt hashing
- ✅ **Rate Limiting** - API rate limiting
- ✅ **HTTPS/SSL** - Encrypted connections

### Files
- `helpers/Security.php` - Security utilities
- `.htaccess` - Apache security rules
- `config/email_config.php` - Secure email config

---

## 📊 Performance

### Optimizations
- CSS/JS minification
- Image lazy loading
- Database query optimization
- Browser caching headers
- GZIP compression
- Connection pooling

### Monitoring
- Server logs: `/var/log/apache2/`
- Database logs: `/var/log/mysql/`
- Application logs: `/var/www/choviet/logs/`

---

## 🐛 Troubleshooting

### Common Issues

**1. Database Connection Error**
```bash
# Check MySQL
systemctl status mysql

# Verify credentials in model/mConnect.php
```

**2. WebSocket Connection Error**
```bash
# Check Node.js server
pm2 list

# View logs
pm2 logs choviet-websocket
```

**3. 404 Not Found**
```bash
# Check .htaccess is loaded
a2enmod rewrite
systemctl restart apache2

# Check Apache error log
tail -f /var/log/apache2/choviet-error.log
```

**4. Email Not Sending**
```bash
# Verify Gmail App Password
# Check SMTP credentials in config/email_config.php
# Ensure port 587 is open
```

**Xem** `USEFUL_COMMANDS.md` **cho troubleshooting chi tiết**

---

## 📚 Documentation

| File | Mô Tả |
|------|-------|
| `QUICK_START.md` | Deploy trong 30 phút |
| `DEPLOYMENT_GUIDE_VI.md` | Hướng dẫn chi tiết (70+ trang) |
| `SOURCE_CODE_OVERVIEW_VI.md` | Tổng quan source code |
| `USEFUL_COMMANDS.md` | Các lệnh hữu ích |
| `setup_server.sh` | Automated setup script (Linux) |
| `deploy.ps1` | Deployment helper (Windows) |

---

## 🌐 Live Demo & URLs

### Local Development
- Homepage: `http://localhost/choviet2912`
- Admin: `http://localhost/choviet2912/admin.php`
- Login: `http://localhost/choviet2912/loginlogout/login.php`

### Production (after deployment)
- Homepage: `https://yourdomain.com`
- Admin: `https://yourdomain.com/admin.php`
- API: `https://yourdomain.com/api/`

---

## 📞 Support & Contribution

### Getting Help
1. Check documentation files
2. Read inline code comments
3. Check GitHub Issues
4. Contact author

### Contributing
1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

### Reporting Bugs
- Create GitHub Issue
- Include error message & steps to reproduce
- Include server info & logs

---

## 📋 Deployment Checklist

### Pre-deployment
- [ ] Read: `QUICK_START.md` or `DEPLOYMENT_GUIDE_VI.md`
- [ ] Backup local database
- [ ] Update credentials
- [ ] Test locally

### During Deployment
- [ ] Create DigitalOcean Droplet
- [ ] SSH connection works
- [ ] Run setup script
- [ ] Configure database
- [ ] Configure email
- [ ] Setup SSL

### Post-deployment
- [ ] Test website
- [ ] Test all features
- [ ] Configure DNS
- [ ] Monitor logs
- [ ] Setup backups
- [ ] Setup monitoring

---

## 🔄 Update & Maintenance

### Weekly
- Check error logs
- Monitor server resources
- Verify backups

### Monthly
- Update packages: `apt upgrade -y`
- Backup database
- Check SSL certificate: `certbot certificates`

### Quarterly
- Major updates
- Performance optimization
- Security audit

---

## 🎯 Roadmap

### Current (v1.0)
- ✅ Marketplace features
- ✅ Real-time chat
- ✅ Payment integration
- ✅ Admin panel

### Future (v1.1+)
- 🚀 Mobile app
- 🚀 Advanced analytics
- 🚀 Recommendation engine
- 🚀 Multiple payment methods
- 🚀 Automated marketing
- 🚀 Multi-language support

---

## 📄 License

This project is licensed under the MIT License - see LICENSE file for details.

---

## 👤 Author

**HoangAn2912**
- GitHub: https://github.com/HoangAn2912
- Repository: https://github.com/HoangAn2912/muabandocu

---

## 📞 Quick Links

| Resource | URL |
|----------|-----|
| GitHub | https://github.com/HoangAn2912/muabandocu |
| DigitalOcean | https://www.digitalocean.com |
| Apache Docs | https://httpd.apache.org/docs/ |
| PHP Docs | https://www.php.net/docs.php |
| MySQL Docs | https://dev.mysql.com/doc/ |
| Node.js Docs | https://nodejs.org/docs/ |

---

## 🙏 Cảm ơn

Cảm ơn bạn đã sử dụng **Chợ Việt** marketplace platform!

**Hãy star ⭐ project này nếu thấy hữu ích!**

---

## 📝 Changelog

### v1.0 (Current)
- Initial release
- Marketplace features
- Real-time chat
- Payment integration
- Admin panel
- Comprehensive documentation
- Deployment guides

### v0.9
- Beta version
- Core features
- Local development setup

---

## 🚀 Bắt Đầu Ngay!

### Para sa quick deployment:
```
📖 Read: QUICK_START.md (30 minutes)
```

### Para sa detailed guide:
```
📖 Read: DEPLOYMENT_GUIDE_VI.md (comprehensive)
```

### Para sa source code:
```
📖 Read: SOURCE_CODE_OVERVIEW_VI.md
```

### Para sa commands:
```
📖 Read: USEFUL_COMMANDS.md
```

---

**Happy deploying! 🎉**

For more information, visit the [documentation files](https://github.com/HoangAn2912/muabandocu) or check the inline comments in the source code.
