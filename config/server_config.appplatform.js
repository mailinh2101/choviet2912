// Cấu hình môi trường cho Node.js WebSocket Server - APP PLATFORM
// File này được sử dụng trên DigitalOcean App Platform

module.exports = {
  // Hostname cho web server (internal service communication)
  // App Platform sử dụng internal URLs: http://web:8080
  hostname: process.env.WEB_SERVICE_HOST || 'web',
  
  // Port cho web server (PHP service)
  port: process.env.WEB_SERVICE_PORT || 8080,
  
  // Base path cho project (App Platform deploy ở root)
  basePath: '',
  
  // Port cho WebSocket server (App Platform auto-assigns)
  wsPort: parseInt(process.env.PORT || process.env.WS_PORT || '3000'),
  
  // Secret để ký HMAC token cho WebSocket
  // ⚠️ Phải set trong App Platform Environment Variables
  wsSecret: process.env.WS_SECRET || '',
  
  // Đường dẫn tuyệt đối đến thư mục project
  // App Platform uses /workspace as working directory
  projectRoot: process.env.PROJECT_ROOT || '/workspace',
  
  // Đường dẫn đến thư mục chat
  chatPath: process.env.CHAT_PATH || '/workspace/chat',
  
  // Database connection (from App Platform DATABASE_URL)
  database: {
    host: process.env.DB_HOST || process.env.DATABASE_HOST || 'localhost',
    port: process.env.DB_PORT || process.env.DATABASE_PORT || 3306,
    user: process.env.DB_USER || process.env.DATABASE_USER || 'root',
    password: process.env.DB_PASSWORD || process.env.DATABASE_PASSWORD || '',
    database: process.env.DB_NAME || process.env.DATABASE_NAME || 'choviet29_db'
  },
  
  // CORS settings (App Platform auto-assigns domain)
  allowedOrigins: [
    process.env.APP_URL || 'https://sea-lion-app-oa3de.ondigitalocean.app',
    'https://*.ondigitalocean.app'
  ],
  
  // App Platform specific
  isAppPlatform: true,
  environment: process.env.APP_ENV || process.env.NODE_ENV || 'production'
};
