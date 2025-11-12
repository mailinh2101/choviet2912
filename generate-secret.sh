#!/bin/bash
# Script để generate secret key cho WebSocket authentication
# Chạy trên server: bash generate-secret.sh

echo "🔑 Generating WebSocket Secret Key..."
echo ""

SECRET=$(node -e "console.log(require('crypto').randomBytes(32).toString('hex'))")

echo "Your new WebSocket secret key:"
echo "================================"
echo "$SECRET"
echo "================================"
echo ""
echo "📝 Copy secret này vào file config/server_config.js:"
echo "wsSecret: '$SECRET'"
echo ""
