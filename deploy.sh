#!/bin/bash
# Script deploy tự động cho DigitalOcean
# Chạy script này trên server sau khi đã setup lần đầu

echo "🚀 Bắt đầu deploy choviet2912..."

# Màu sắc cho output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Thư mục project
PROJECT_DIR="/var/www/choviet2912"
cd "$PROJECT_DIR" || exit 1

echo -e "${YELLOW}📥 Pulling latest code from Git...${NC}"
git fetch origin
git reset --hard origin/main
git pull origin main

echo -e "${YELLOW}📦 Installing Node.js dependencies...${NC}"
npm install --production

echo -e "${YELLOW}🔧 Setting permissions...${NC}"
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"

# Tạo thư mục chat nếu chưa có
if [ ! -d "$PROJECT_DIR/chat" ]; then
    echo -e "${YELLOW}📁 Creating chat directory...${NC}"
    sudo mkdir -p "$PROJECT_DIR/chat"
    sudo chown -R www-data:www-data "$PROJECT_DIR/chat"
    sudo chmod -R 775 "$PROJECT_DIR/chat"
fi

echo -e "${YELLOW}🔄 Restarting Node.js WebSocket server...${NC}"
pm2 reload choviet-ws --update-env || pm2 start js/server.js --name choviet-ws

echo -e "${YELLOW}💾 Saving PM2 process list...${NC}"
pm2 save

echo -e "${YELLOW}🔄 Reloading Nginx...${NC}"
sudo nginx -t && sudo systemctl reload nginx

echo -e "${YELLOW}📊 Checking PM2 status...${NC}"
pm2 status

echo -e "${GREEN}✅ Deploy completed successfully!${NC}"
echo -e "${GREEN}📝 Check logs with: pm2 logs choviet-ws${NC}"
