#!/bin/bash

# Chợ Việt - DigitalOcean Server Setup Script
# Run this script on the DigitalOcean Droplet after SSH connection
# Usage: bash setup_server.sh

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="${1:localhost}"
APP_DIR="/var/www/choviet"
DB_NAME="choviet29"
DB_USER="choviet_user"
DB_PASSWORD="${2:choviet_secure_2024}"

echo -e "${BLUE}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║     Chợ Việt - DigitalOcean Server Setup Script               ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════════╝${NC}"

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}✗ Please run this script as root (use sudo)${NC}"
    exit 1
fi

# Step 1: Update system
echo -e "\n${YELLOW}[1/12] Updating system packages...${NC}"
apt update && apt upgrade -y
echo -e "${GREEN}✓ System updated${NC}"

# Step 2: Install basic tools
echo -e "\n${YELLOW}[2/12] Installing basic tools...${NC}"
apt install -y curl wget git vim nano htop unzip zip net-tools
echo -e "${GREEN}✓ Basic tools installed${NC}"

# Step 3: Install Apache and PHP
echo -e "\n${YELLOW}[3/12] Installing Apache and PHP 8.0...${NC}"
apt install -y apache2
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.0 php8.0-mysql php8.0-curl php8.0-gd php8.0-mbstring php8.0-zip php8.0-json php8.0-xml php8.0-fpm
a2enmod rewrite
a2enmod ssl
a2enmod headers
a2enmod proxy
a2enmod proxy_fcgi
a2enmod setenvif
systemctl restart apache2
echo -e "${GREEN}✓ Apache and PHP installed${NC}"

# Step 4: Install MySQL/MariaDB
echo -e "\n${YELLOW}[4/12] Installing MySQL...${NC}"
apt install -y mysql-server
echo -e "${GREEN}✓ MySQL installed${NC}"

# Step 5: Secure MySQL (skip interactive prompts for automation)
echo -e "\n${YELLOW}[5/12] Securing MySQL...${NC}"
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root_password';"
mysql -e "FLUSH PRIVILEGES;"
echo -e "${GREEN}✓ MySQL secured${NC}"

# Step 6: Create database and user
echo -e "\n${YELLOW}[6/12] Creating database and user...${NC}"
mysql -u root -proot_password <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS '$DB_USER'@'localhost';
CREATE USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF
echo -e "${GREEN}✓ Database and user created${NC}"

# Step 7: Install Node.js
echo -e "\n${YELLOW}[7/12] Installing Node.js...${NC}"
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
apt install -y nodejs
npm install -g pm2
echo -e "${GREEN}✓ Node.js and PM2 installed${NC}"

# Step 8: Install Composer
echo -e "\n${YELLOW}[8/12] Installing Composer...${NC}"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
echo -e "${GREEN}✓ Composer installed${NC}"

# Step 9: Clone repository
echo -e "\n${YELLOW}[9/12] Cloning repository...${NC}"
mkdir -p /var/www
cd /var/www
if [ -d "choviet" ]; then
    echo "Directory exists, pulling latest updates..."
    cd choviet
    git pull origin main
else
    git clone https://github.com/HoangAn2912/muabandocu.git choviet
    cd choviet
fi
echo -e "${GREEN}✓ Repository cloned${NC}"

# Step 10: Install dependencies
echo -e "\n${YELLOW}[10/12] Installing PHP and Node.js dependencies...${NC}"
composer install
npm install
echo -e "${GREEN}✓ Dependencies installed${NC}"

# Step 11: Set permissions
echo -e "\n${YELLOW}[11/12] Setting file permissions...${NC}"
chown -R www-data:www-data /var/www/choviet
chmod -R 755 /var/www/choviet
chmod -R 775 /var/www/choviet/logs
chmod -R 775 /var/www/choviet/chat
chmod -R 775 /var/www/choviet/vendor
chmod 750 /var/www/choviet/config
chmod 750 /var/www/choviet/model
chmod 750 /var/www/choviet/controller
echo -e "${GREEN}✓ Permissions set${NC}"

# Step 12: Configure Apache Virtual Host
echo -e "\n${YELLOW}[12/12] Configuring Apache virtual host...${NC}"
cat > /etc/apache2/sites-available/choviet.conf <<'VHOST'
<VirtualHost *:80>
    ServerName DOMAIN_PLACEHOLDER
    ServerAlias www.DOMAIN_PLACEHOLDER
    ServerAdmin admin@DOMAIN_PLACEHOLDER
    
    DocumentRoot /var/www/choviet
    
    <Directory /var/www/choviet>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/choviet-error.log
    CustomLog ${APACHE_LOG_DIR}/choviet-access.log combined
    
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
    </IfModule>
    
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
VHOST

# Replace domain placeholder
sed -i "s|DOMAIN_PLACEHOLDER|$DOMAIN|g" /etc/apache2/sites-available/choviet.conf

a2ensite choviet.conf
a2dissite 000-default.conf
apache2ctl configtest
systemctl restart apache2
echo -e "${GREEN}✓ Apache virtual host configured${NC}"

# Create necessary directories
echo -e "\n${YELLOW}Creating directories...${NC}"
mkdir -p /var/www/choviet/logs
mkdir -p /var/www/choviet/temp
mkdir -p /var/www/choviet/chat
echo -e "${GREEN}✓ Directories created${NC}"

# Display summary
echo -e "\n${BLUE}╔════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                    ✓ Setup Complete!                           ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════════╝${NC}"

echo -e "\n${GREEN}Configuration Summary:${NC}"
echo -e "  Domain: ${YELLOW}$DOMAIN${NC}"
echo -e "  App Dir: ${YELLOW}$APP_DIR${NC}"
echo -e "  Database: ${YELLOW}$DB_NAME${NC}"
echo -e "  DB User: ${YELLOW}$DB_USER${NC}"
echo -e "  DB Pass: ${YELLOW}$DB_PASSWORD${NC}"

echo -e "\n${YELLOW}Next Steps:${NC}"
echo -e "1. ${BLUE}Update database credentials in: /var/www/choviet/model/mConnect.php${NC}"
echo -e "2. ${BLUE}Update email credentials in: /var/www/choviet/config/email_config.php${NC}"
echo -e "3. ${BLUE}Setup SSL: sudo certbot --apache -d $DOMAIN${NC}"
echo -e "4. ${BLUE}Import database: mysql -u $DB_USER -p $DB_NAME < /var/www/choviet/data/choviet29.sql${NC}"
echo -e "5. ${BLUE}Start WebSocket: cd /var/www/choviet && pm2 start js/server.js${NC}"
echo -e "6. ${BLUE}Test: curl http://$DOMAIN${NC}"

echo -e "\n${YELLOW}Useful Commands:${NC}"
echo -e "  ${BLUE}Start services:${NC} systemctl restart apache2 && systemctl restart mysql"
echo -e "  ${BLUE}View logs:${NC} tail -f /var/log/apache2/choviet-error.log"
echo -e "  ${BLUE}Check database:${NC} mysql -u $DB_USER -p -e 'SHOW DATABASES;'"
echo -e "  ${BLUE}WebSocket status:${NC} pm2 list"
echo -e "  ${BLUE}Reload firewall:${NC} ufw reload"

echo -e "\n${BLUE}For more details, see: /var/www/choviet/DEPLOYMENT_GUIDE_VI.md${NC}\n"
