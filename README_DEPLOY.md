# 🚀 ChoViet29 - Marketplace với Livestream & Chat Realtime

Website marketplace với tính năng livestream bán hàng và chat realtime sử dụng PHP, Node.js WebSocket, và MySQL.

## ✨ Tính Năng Chính

- 🛒 **Marketplace**: Mua bán sản phẩm
- 📺 **Livestream**: Bán hàng trực tiếp qua livestream
- 💬 **Chat Realtime**: WebSocket chat giữa người mua và người bán
- 🔐 **Authentication**: Đăng nhập, đăng ký, quản lý profile
- 💳 **Payment**: Tích hợp VNPay
- 📊 **Dashboard**: Quản lý đơn hàng, doanh thu, sản phẩm

## 🏗️ Tech Stack

### Backend
- **PHP 8.1+**: Server-side logic
- **Node.js 18+**: WebSocket server (chat & livestream)
- **MySQL**: Database
- **Nginx**: Web server & reverse proxy

### Frontend
- **HTML/CSS/JavaScript**: UI
- **Bootstrap**: CSS framework
- **WebSocket API**: Realtime communication

### Tools
- **PM2**: Process manager cho Node.js
- **Composer**: PHP package manager
- **NPM**: Node package manager

## 📦 Dependencies

### Node.js (package.json)
- `ws`: WebSocket library

### PHP
- `php8.1-fpm`
- `php8.1-mysql`
- `php8.1-mbstring`
- `php8.1-xml`
- `php8.1-curl`

## 🚀 Quick Start - Development

### Prerequisites
- PHP 8.1+
- Node.js 18+
- MySQL 8+
- Composer (optional)

### Setup

1. **Clone repository**
```bash
git clone https://github.com/mailinh2101/choviet2912.git
cd choviet2912
```

2. **Install dependencies**
```bash
npm install
```

3. **Setup database**
```bash
mysql -u root -p
CREATE DATABASE choviet29_db;
USE choviet29_db;
SOURCE data/choviet29.sql;
```

4. **Configure database connection**
Edit `model/connectdb.php`:
```php
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "choviet29_db";
```

5. **Start Node.js WebSocket server**
```bash
node js/server.js
```

6. **Start PHP server**
```bash
# Using Laragon, XAMPP, or:
php -S localhost:8080
```

7. **Access website**
```
http://localhost:8080
```

## 🌐 Deploy to Production (DigitalOcean)

Có 3 file hướng dẫn deploy chi tiết:

1. **📚 DEPLOY_DIGITALOCEAN.md** - Hướng dẫn đầy đủ từng bước (11 bước)
2. **⚡ DEPLOY_QUICK_START.md** - Quick reference nhanh
3. **✅ DEPLOY_CHECKLIST.md** - Checklist để tick từng bước

### Quick Deploy Steps

```bash
# 1. Tạo Droplet Ubuntu 22.04 trên DigitalOcean
# 2. Point domain về IP droplet
# 3. SSH vào server

# 4. Install môi trường
sudo apt update && sudo apt upgrade -y
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs nginx mysql-server php8.1-fpm php8.1-mysql git
sudo npm install -g pm2

# 5. Clone project
cd /var/www
sudo git clone https://github.com/mailinh2101/choviet2912.git choviet2912
cd choviet2912
npm install

# 6. Config production
sudo cp config/server_config.production.js config/server_config.js
sudo nano config/server_config.js  # Update domain, secret, paths

# 7. Setup database
mysql -u root -p < data/choviet29.sql

# 8. Start Node server
pm2 start js/server.js --name choviet-ws
pm2 save
pm2 startup systemd

# 9. Configure Nginx
sudo nano /etc/nginx/sites-available/choviet2912
# Copy from nginx.conf.example, update domain
sudo ln -s /etc/nginx/sites-available/choviet2912 /etc/nginx/sites-enabled/
sudo systemctl restart nginx

# 10. Setup SSL
sudo certbot --nginx -d your-domain.com
```

**Xem chi tiết tại:** [DEPLOY_DIGITALOCEAN.md](DEPLOY_DIGITALOCEAN.md)

## 📁 Project Structure

```
choviet2912/
├── api/                    # API endpoints (PHP)
│   ├── chat-api.php
│   ├── livestream-api.php
│   └── ...
├── config/                 # Configuration files
│   ├── server_config.js    # Node server config (gitignored)
│   ├── server_config.production.js
│   └── email_config.php
├── controller/             # PHP controllers
├── model/                  # PHP models
├── view/                   # PHP views
│   ├── livestream_viewer.php
│   ├── livestream_broadcast.php
│   └── ...
├── js/                     # JavaScript files
│   ├── server.js          # Node.js WebSocket server
│   ├── chat.js
│   └── ...
├── css/                    # Stylesheets
├── img/                    # Images (gitignored)
├── chat/                   # Chat data files (gitignored)
├── logs/                   # Log files (gitignored)
├── data/                   # Database schemas
│   └── choviet29.sql
├── deploy.sh              # Auto-deploy script
├── nginx.conf.example     # Nginx config template
├── .env.example           # Environment variables template
├── .gitignore
├── package.json
└── README.md
```

## 🔧 Configuration Files

### Development
- `config/server_config.js` - Copy từ `.production.js` và edit
- `model/connectdb.php` - Database credentials

### Production (Deploy)
- `config/server_config.production.js` → copy thành `server_config.js`
- `nginx.conf.example` → `/etc/nginx/sites-available/choviet2912`
- `.env.example` → `.env`

## 🌐 WebSocket Architecture

### Development
```
Client → ws://localhost:3000 → Node.js Server
```

### Production
```
Client → wss://domain.com/ws/ → Nginx Proxy → http://localhost:3000 → Node.js Server
```

Code tự động detect môi trường:
```javascript
function getWebSocketURL() {
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const hostname = window.location.hostname;
    
    if (hostname === 'localhost' || hostname === '127.0.0.1') {
        return 'ws://localhost:3000';
    }
    
    return `${protocol}//${hostname}/ws/`;
}
```

## 🔐 Security

- ✅ WebSocket authentication với HMAC (nếu set wsSecret)
- ✅ SSL/TLS với Let's Encrypt
- ✅ Firewall (UFW) chỉ mở port cần thiết
- ✅ `.gitignore` bảo vệ file nhạy cảm
- ✅ Nginx security headers

## 🐛 Troubleshooting

### WebSocket không kết nối
```bash
# Check Node server
pm2 logs choviet-ws

# Check port
sudo ss -tulpn | grep 3000

# Test connection
wscat -c ws://localhost:3000
```

### Nginx 502 Bad Gateway
```bash
# Check services
pm2 status
sudo systemctl status nginx

# Check logs
sudo tail -f /var/log/nginx/error.log
```

### Database connection error
```bash
# Check MySQL
sudo systemctl status mysql

# Test connection
mysql -u username -p database_name
```

**Xem thêm:** Section "Các Lỗi Thường Gặp" trong [DEPLOY_DIGITALOCEAN.md](DEPLOY_DIGITALOCEAN.md)

## 📝 Scripts

### Development
```bash
# Start Node server
node js/server.js

# Start with auto-restart (nodemon)
npm install -g nodemon
nodemon js/server.js
```

### Production
```bash
# Deploy updates
./deploy.sh

# Manual commands
git pull origin main
npm install
pm2 reload choviet-ws
sudo systemctl reload nginx

# View logs
pm2 logs choviet-ws
sudo tail -f /var/log/nginx/error.log

# Restart services
pm2 restart choviet-ws
sudo systemctl restart nginx
```

## 📊 Monitoring

### PM2
```bash
pm2 status              # Process status
pm2 logs choviet-ws     # View logs
pm2 monit               # Real-time monitoring
```

### System
```bash
# Service status
sudo systemctl status nginx
sudo systemctl status mysql
sudo systemctl status php8.1-fpm

# Resource usage
htop
df -h
free -m
```

## 🔄 Update & Maintenance

### Regular Updates
```bash
# System packages
sudo apt update && sudo apt upgrade

# Node packages
npm update

# SSL renewal (automatic)
sudo certbot renew --dry-run
```

### Database Backup
```bash
# Backup
mysqldump -u user -p database_name > backup_$(date +%Y%m%d).sql

# Restore
mysql -u user -p database_name < backup_20250112.sql
```

## 📚 Documentation

- **DEPLOY_DIGITALOCEAN.md** - Full deployment guide (11 steps)
- **DEPLOY_QUICK_START.md** - Quick reference
- **DEPLOY_CHECKLIST.md** - Step-by-step checklist
- **DEPLOY_SUMMARY.md** - Changes summary
- **INDEX_DOCUMENTATION.md** - Code structure
- **SOURCE_CODE_OVERVIEW_VI.md** - Vietnamese overview

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is private.

## 👥 Authors

- **Mailinh2101** - [GitHub](https://github.com/mailinh2101)

## 🙏 Acknowledgments

- Bootstrap for UI framework
- `ws` library for WebSocket
- Let's Encrypt for free SSL
- DigitalOcean for hosting

---

**📞 Support**: Xem các file DEPLOY_*.md để được hỗ trợ deploy

**🐛 Issues**: Report tại [GitHub Issues](https://github.com/mailinh2101/choviet2912/issues)

**⭐ Star this repo** nếu thấy hữu ích!
