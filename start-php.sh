#!/bin/bash
# Start script for PHP service on App Platform

echo "🚀 Starting PHP application on App Platform..."

# Create required directories
mkdir -p chat logs img
chmod -R 755 chat logs img

# Set permissions
echo "📁 Setting up directories..."

# Start PHP built-in server
echo "🔥 Starting PHP server on port 8080..."
exec php -S 0.0.0.0:8080 -t .
