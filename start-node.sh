#!/bin/bash
# Start script for Node.js WebSocket service on App Platform

echo "🚀 Starting Node.js WebSocket server on App Platform..."

# Create required directories
mkdir -p chat logs
chmod -R 755 chat logs

# Start Node server
echo "🔥 Starting WebSocket server on port ${WS_PORT:-3000}..."
exec node js/server.js
