// Cấu hình môi trường cho Node.js WebSocket Server - PRODUCTION (DigitalOcean)
// Đổi tên file này thành server_config.js khi deploy lên server production

module.exports = {
  // Hostname cho web server (localhost vì PHP và Node cùng server)
  hostname: '127.0.0.1',
  
  // Port cho web server (Nginx sẽ proxy từ 80/443)
  port: 80,
  
  // Base path cho project (để rỗng nếu deploy ở root domain)
  basePath: '',
  
  // Port cho WebSocket server (Node server sẽ chạy trên port này)
  wsPort: 3000,
  
  // Secret để ký HMAC token cho WebSocket
  // ⚠️ QUAN TRỌNG: Thay đổi secret này thành chuỗi ngẫu nhiên mạnh
  // Tạo secret: node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
  wsSecret: 'CHANGE_THIS_TO_RANDOM_SECRET_KEY',
  
  // Đường dẫn tuyệt đối đến thư mục project trên server
  // ⚠️ Thay đổi theo đường dẫn thực tế trên DigitalOcean droplet
  projectRoot: '/var/www/choviet2912',
  
  // Đường dẫn đến thư mục chat
  chatPath: '/var/www/choviet2912/chat',
  
  // CORS settings (nếu cần)
  allowedOrigins: [
    'https://your-domain.com',
    'https://www.your-domain.com'
  ]
};
