# 🔧 Các Lệnh Hữu Ích cho Deployment

## 📋 Mục Lục
- [DigitalOcean Setup](#digitalocean-setup)
- [SSH & Access](#ssh--access)
- [Apache Commands](#apache-commands)
- [MySQL Commands](#mysql-commands)
- [Node.js Commands](#nodejs-commands)
- [Git Commands](#git-commands)
- [File & Permissions](#file--permissions)
- [Monitoring & Logs](#monitoring--logs)
- [Backup & Restore](#backup--restore)
- [SSL/HTTPS](#sslhttps)
- [Firewall](#firewall)

---

## 🌐 DigitalOcean Setup

### Khởi tạo Droplet
```bash
# SSH vào Droplet mới
ssh root@YOUR_DROPLET_IP

# Update system
apt update && apt upgrade -y

# Cài basic tools
apt install -y curl wget git vim nano htop
```

---

## 🔐 SSH & Access

### Tạo SSH Key (trên local machine)
```bash
# Windows PowerShell
ssh-keygen -t rsa -b 4096 -f $env:USERPROFILE\.ssh\id_rsa

# Linux/Mac
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa
```

### Thêm SSH Key vào Droplet
```bash
# Copy public key (run on local machine)
cat ~/.ssh/id_rsa.pub | ssh root@YOUR_DROPLET_IP "cat >> ~/.ssh/authorized_keys"

# Hoặc manual:
ssh root@YOUR_DROPLET_IP
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Paste public key content
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

### Disable password SSH (recommended)
```bash
# On server
nano /etc/ssh/sshd_config

# Change:
# PasswordAuthentication yes
# TO:
# PasswordAuthentication no
# PubkeyAuthentication yes

# Restart SSH
systemctl restart ssh

# Test (on local machine)
ssh -i ~/.ssh/id_rsa root@YOUR_DROPLET_IP
```

---

## 🔧 Apache Commands

### Cài đặt Apache
```bash
apt install -y apache2

# Enable modules
a2enmod rewrite
a2enmod ssl
a2enmod headers
a2enmod proxy
a2enmod proxy_fcgi
a2enmod deflate
a2enmod expires
```

### Quản lý Apache
```bash
# Start/Stop/Restart
systemctl start apache2
systemctl stop apache2
systemctl restart apache2

# Status
systemctl status apache2
apache2ctl status

# Enable on boot
systemctl enable apache2

# Kiểm tra syntax
apache2ctl configtest

# List virtual hosts
apache2ctl -S

# Reload config (without restart)
apache2ctl graceful
```

### Virtual Host Management
```bash
# Enable site
a2ensite choviet.conf

# Disable site
a2dissite choviet.conf

# List sites
apache2ctl -S

# Test config
apache2ctl configtest

# Restart Apache
systemctl restart apache2
```

### Apache Logs
```bash
# Real-time error log
tail -f /var/log/apache2/choviet-error.log

# Real-time access log
tail -f /var/log/apache2/choviet-access.log

# Last 100 lines
tail -100 /var/log/apache2/choviet-error.log

# Search for errors
grep ERROR /var/log/apache2/choviet-error.log

# Count errors
grep ERROR /var/log/apache2/choviet-error.log | wc -l
```

---

## 💾 MySQL Commands

### Cài đặt MySQL
```bash
apt install -y mysql-server

# Secure installation
mysql_secure_installation
```

### MySQL Client
```bash
# Connect to MySQL
mysql -u username -p
mysql -u root -p

# Connect to specific database
mysql -u username -p database_name

# Connect to remote server
mysql -u username -p -h server_ip database_name
```

### Database Operations
```bash
# Login
mysql -u root -p

# Inside MySQL shell:

# List databases
SHOW DATABASES;

# Select database
USE choviet29;

# Show tables
SHOW TABLES;

# Describe table
DESCRIBE users;

# Show table structure
SHOW CREATE TABLE users\G

# Count records
SELECT COUNT(*) FROM users;

# Show users
SELECT User, Host FROM mysql.user;

# Create database
CREATE DATABASE choviet29 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user
CREATE USER 'choviet_user'@'localhost' IDENTIFIED BY 'password';

# Grant privileges
GRANT ALL PRIVILEGES ON choviet29.* TO 'choviet_user'@'localhost';

# Flush privileges
FLUSH PRIVILEGES;

# Drop user
DROP USER 'choviet_user'@'localhost';

# Change password
ALTER USER 'choviet_user'@'localhost' IDENTIFIED BY 'new_password';

# Exit
EXIT;
quit;
```

### Database Backup & Restore
```bash
# Backup database
mysqldump -u choviet_user -p choviet29 > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup all databases
mysqldump -u root -p --all-databases > all_databases.sql

# Restore database
mysql -u choviet_user -p choviet29 < backup_20240101_120000.sql

# Backup specific tables
mysqldump -u choviet_user -p choviet29 users products > users_products.sql

# Backup with gzip compression
mysqldump -u choviet_user -p choviet29 | gzip > backup.sql.gz

# Restore from gzip
gunzip < backup.sql.gz | mysql -u choviet_user -p choviet29
```

### MySQL Service
```bash
# Start/Stop/Restart
systemctl start mysql
systemctl stop mysql
systemctl restart mysql

# Status
systemctl status mysql
mysql -u root -p -e "SELECT VERSION();"

# Enable on boot
systemctl enable mysql

# View MySQL logs
tail -f /var/log/mysql/error.log
```

---

## 📦 Node.js Commands

### Cài đặt Node.js & npm
```bash
# Install Node.js 16
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
apt install -y nodejs

# Install npm globally
npm install -g npm

# Update npm
npm install -g npm@latest
```

### npm Commands
```bash
# Install dependencies
npm install
npm install --production

# Install specific package
npm install package-name

# Install globally
npm install -g pm2

# List installed packages
npm list
npm list -g

# Update packages
npm update

# Remove package
npm uninstall package-name
```

### PM2 (Process Manager)
```bash
# Install PM2 globally
npm install -g pm2

# Start application
pm2 start js/server.js --name "choviet-websocket"

# List running processes
pm2 list

# View process details
pm2 show choviet-websocket

# View logs
pm2 logs choviet-websocket
pm2 logs choviet-websocket --lines 100

# Stop process
pm2 stop choviet-websocket

# Restart process
pm2 restart choviet-websocket

# Delete process
pm2 delete choviet-websocket

# Save PM2 config
pm2 save

# Setup auto-startup on reboot
pm2 startup
pm2 startup ubuntu -u www-data --hp /var/www

# Monitor all processes
pm2 monit

# Kill all processes
pm2 kill
```

---

## 🔄 Git Commands

### Git Setup
```bash
# Configure Git
git config --global user.name "Your Name"
git config --global user.email "your@email.com"

# Check configuration
git config --list
```

### Git Operations
```bash
# Clone repository
git clone https://github.com/HoangAn2912/muabandocu.git

# Check status
git status

# Add files
git add .
git add filename.php

# Commit
git commit -m "Update: description"

# Push to remote
git push origin main

# Pull from remote
git pull origin main

# View commit log
git log
git log --oneline -10

# View branches
git branch -a

# Create branch
git checkout -b feature-name

# Switch branch
git checkout main

# Merge branch
git merge feature-name

# Delete branch
git branch -d feature-name

# View diff
git diff
git diff filename.php

# Discard changes
git checkout filename.php

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1
```

### Git Deployment
```bash
# SSH key for GitHub
ssh-keygen -t rsa -f ~/.ssh/github_rsa

# Add to SSH agent
eval $(ssh-agent -s)
ssh-add ~/.ssh/github_rsa

# Test connection
ssh -T git@github.com

# Clone with SSH (no password needed)
git clone git@github.com:HoangAn2912/muabandocu.git
```

---

## 📁 File & Permissions

### File Operations
```bash
# List files
ls -la
ls -lh

# Change directory
cd /var/www/choviet

# Create directory
mkdir -p /var/www/choviet/logs

# Create file
touch filename.php

# View file
cat filename.php
head filename.php
tail -20 filename.php

# Edit file
nano filename.php
vim filename.php

# Copy file
cp source.php dest.php

# Copy directory
cp -r source_dir/ dest_dir/

# Move/rename file
mv old_name.php new_name.php

# Delete file
rm filename.php

# Delete directory
rm -rf directory_name/

# Search files
find . -name "*.php" -type f
find . -name "*config*" -type f

# Count files
find . -name "*.php" | wc -l
```

### Permissions
```bash
# Change ownership
chown username:group filename
chown -R www-data:www-data /var/www/choviet

# Change permissions
chmod 755 directory
chmod 644 file.php
chmod 775 logs/
chmod 600 config/

# Symbolic permissions
chmod u+rwx,g+rx,o+rx directory     # 755
chmod u+rw,g+r,o+r file.php        # 644
chmod u+rwx,g+rwx,o+rx directory   # 775

# View permissions
ls -la

# Find files with specific permissions
find . -perm 644

# Find world-writable files
find . -perm -002
```

---

## 📊 Monitoring & Logs

### System Monitoring
```bash
# Real-time monitoring
top
htop

# Memory usage
free -h
free -m

# Disk usage
df -h
du -sh *

# Disk I/O
iostat

# Network
netstat -tulpn
ss -tulpn

# Process info
ps aux
ps aux | grep php
ps aux | grep node

# View processes
pgrep -a php
pgrep -a node

# Kill process
kill -9 PID
killall node
```

### Check Ports
```bash
# Check if port is in use
lsof -i :80
lsof -i :443
lsof -i :3000
lsof -i :3306

# List all listening ports
netstat -tulpn
ss -tulpn

# Check specific port
ss -tulpn | grep :80
```

### Log Viewing
```bash
# Real-time Apache error log
tail -f /var/log/apache2/choviet-error.log

# Real-time access log
tail -f /var/log/apache2/choviet-access.log

# Last N lines
tail -100 /var/log/apache2/choviet-error.log
tail -20 /var/log/apache2/choviet-access.log

# Search in logs
grep "error" /var/log/apache2/choviet-error.log
grep "GET" /var/log/apache2/choviet-access.log

# Count log entries
wc -l /var/log/apache2/choviet-access.log

# MySQL logs
tail -f /var/log/mysql/error.log

# PHP logs
tail -f /var/log/php-errors.log

# Syslog
tail -f /var/log/syslog

# Combined log view
tail -f /var/log/apache2/choviet-*.log
```

---

## 💾 Backup & Restore

### Automated Backup with Cron
```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * mysqldump -u choviet_user -pPASSWORD choviet29 | gzip > /backups/choviet29_$(date +\%Y\%m\%d).sql.gz

# Add weekly full server backup
0 3 * * 0 tar -czf /backups/choviet_$(date +\%Y\%m\%d).tar.gz /var/www/choviet

# List cron jobs
crontab -l

# Remove cron job
crontab -r
```

### Manual Backup
```bash
# Backup database
mysqldump -u choviet_user -p choviet29 > choviet_$(date +%Y%m%d_%H%M%S).sql

# Backup website files
tar -czf choviet_files_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/choviet

# Backup everything
tar -czf choviet_full_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/choviet /var/log/apache2/choviet-*

# Backup to remote server
scp choviet_backup.sql user@remote_server:/backups/
```

### Restore Backup
```bash
# Restore database
mysql -u choviet_user -p choviet29 < backup_20240101_120000.sql

# Restore files
tar -xzf choviet_files_20240101.tar.gz -C /

# List tar contents
tar -tzf choviet_files_20240101.tar.gz

# Restore specific files
tar -xzf choviet_files_20240101.tar.gz path/to/file
```

---

## 🔒 SSL/HTTPS

### Let's Encrypt with Certbot
```bash
# Install Certbot
apt install -y certbot python3-certbot-apache

# Get certificate
certbot --apache -d yourdomain.com -d www.yourdomain.com

# Renew certificate
certbot renew

# Renew in dry-run mode
certbot renew --dry-run

# Manual renewal
certbot renew --force-renewal

# List certificates
certbot certificates

# Delete certificate
certbot delete --cert-name yourdomain.com

# Test renewal setup
certbot renew --dry-run

# Auto-renewal with cron
0 12 * * * /usr/bin/certbot renew --quiet
```

### Manual SSL Configuration
```bash
# Generate private key
openssl genrsa -out private.key 2048

# Generate CSR
openssl req -new -key private.key -out request.csr

# Self-signed certificate (for testing)
openssl req -x509 -newkey rsa:2048 -keyout key.pem -out cert.pem -days 365

# View certificate
openssl x509 -text -noout -in cert.pem

# Check certificate expiry
openssl x509 -enddate -noout -in cert.pem
```

---

## 🛡️ Firewall

### UFW (Uncomplicated Firewall)
```bash
# Enable firewall
ufw enable

# Disable firewall
ufw disable

# Check status
ufw status
ufw status verbose

# Allow ports
ufw allow 22/tcp     # SSH
ufw allow 80/tcp     # HTTP
ufw allow 443/tcp    # HTTPS
ufw allow 3000/tcp   # WebSocket

# Allow from specific IP
ufw allow from 192.168.1.100 to any port 22

# Deny port
ufw deny 3306/tcp    # MySQL

# Delete rule
ufw delete allow 3306/tcp

# Reset firewall
ufw reset

# View rules
ufw show added
ufw show numberedrules

# Reload firewall
ufw reload

# Logging
ufw logging on
ufw logging high
```

### iptables (Advanced)
```bash
# List all rules
iptables -L -n
iptables -L -n -v

# List with line numbers
iptables -L -n --line-numbers

# Save rules
iptables-save > /etc/iptables.rules

# Restore rules
iptables-restore < /etc/iptables.rules
```

---

## 🚀 Quick Deployment Checklist

```bash
# 1. SSH into server
ssh root@YOUR_DROPLET_IP

# 2. Run setup script
bash setup_server.sh yourdomain.com secure_db_password

# 3. Update credentials
nano /var/www/choviet/model/mConnect.php
# Update: username, password
nano /var/www/choviet/config/email_config.php
# Update: email, password

# 4. Import database
mysql -u choviet_user -p choviet29 < /var/www/choviet/data/choviet29.sql

# 5. Set up SSL
certbot --apache -d yourdomain.com

# 6. Start WebSocket server
cd /var/www/choviet
pm2 start js/server.js --name "choviet-websocket"
pm2 save

# 7. Test website
curl https://yourdomain.com

# 8. Monitor
pm2 list
tail -f /var/log/apache2/choviet-error.log
```

---

## 📞 Troubleshooting Commands

```bash
# Check Apache
apache2ctl -S
apache2ctl configtest
systemctl status apache2

# Check MySQL
mysql -u root -p -e "SELECT VERSION();"
systemctl status mysql

# Check Node.js
pm2 list
pm2 logs

# Check ports
netstat -tulpn | grep -E "(80|443|3000|3306)"

# Restart all services
systemctl restart apache2 && systemctl restart mysql

# Check disk space
df -h

# Check memory
free -h

# Check logs
tail -f /var/log/apache2/choviet-error.log
tail -f /var/log/mysql/error.log

# Test database connection
mysql -u choviet_user -p choviet29 -e "SELECT COUNT(*) FROM users;"

# Test PHP
php -v
php -r "phpinfo();" | grep -i "loaded configuration"
```

---

**Ghi chú: Thay thế các giá trị placeholder (YOUR_DROPLET_IP, yourdomain.com, PASSWORD, etc.) với giá trị thực tế của bạn**
