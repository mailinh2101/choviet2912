# 📚 Chợ Việt - Tài Liệu Cấu Trúc Source Code

## 📂 Cấu Trúc Dự Án

```
choviet2912/
├── admin/                          # Admin Panel (React/Frontend)
│   ├── package.json               # Dependencies for admin panel
│   ├── docs/                      # Documentation
│   ├── modern_login_form/         # Login form UI
│   └── src/                       # Admin panel source code
│
├── api/                           # API Endpoints
│   ├── chat-api.php              # Chat API endpoints
│   ├── chat-file-api.php         # File upload for chat
│   ├── chat-first-message.php    # Initial message fetch
│   ├── chat-save-filename.php    # Save filename for chat
│   ├── chat-unread.php           # Unread messages count
│   ├── chat-user-info.php        # User info in chat
│   ├── check-reviewed.php        # Check if reviewed
│   ├── create-livestream.php     # Create livestream endpoint
│   ├── livestream-api.php        # Main livestream API
│   ├── livestream-api_backup.php # Backup livestream API
│   ├── review-api.php            # Product review API
│   └── vnpay/                    # VNPay payment integration
│
├── chat/                         # Chat data storage
│   └── chat_4_5.json            # Chat messages JSON file
│
├── config/                       # Configuration files
│   ├── email_config.php          # Email SMTP configuration
│   ├── email_config_mailtrap.php # Mailtrap email config (dev)
│   ├── path_config.php           # Dynamic path configuration
│   ├── server_config.example.js  # Example Node.js config
│   └── server_config.js          # Node.js WebSocket server config
│
├── controller/                   # Business Logic Controllers (MVC)
│   ├── category.php              # Category management (deprecated)
│   ├── cC2COrder.php             # C2C Order management
│   ├── cCategory.php             # Category controller
│   ├── cChat.php                 # Chat operations
│   ├── cDetailProduct.php        # Product detail display
│   ├── cDuyetNapTien.php         # Top-up approval
│   ├── cInventory.php            # Inventory management
│   ├── cKDbaidang.php            # Product dashboard
│   ├── cLivestream.php           # Livestream main controller
│   ├── cLivestream_backup.php    # Livestream backup
│   ├── cLivestream_complex.php   # Complex livestream logic
│   ├── cLivestreamPackage.php    # Livestream package management
│   ├── cLoginLogout.php          # Authentication
│   ├── cOtp.php                  # OTP verification
│   ├── cPost.php                 # Post/listing management
│   ├── cProduct.php              # Product operations
│   ├── cProfile.php              # User profile
│   ├── cQLdanhmuc.php            # Category admin
│   ├── cQLdoanhthu.php           # Revenue admin
│   ├── cQLgiaodich.php           # Transaction admin
│   ├── cQLthongtin.php           # Info admin
│   ├── cReview.php               # Product reviews
│   ├── cSellerDashboard.php      # Seller dashboard
│   ├── cTopUp.php                # Top-up/wallet recharge
│   ├── cUser.php                 # User management
│   └── vnpay/                    # VNPay payment controller
│
├── css/                          # Stylesheets
│   ├── admin-sua.css             # Admin edit page styles
│   ├── admin-them.css            # Admin add page styles
│   ├── admin.css                 # Admin panel styles
│   ├── bootstrap*.css            # Bootstrap framework
│   ├── chat.css                  # Chat styles
│   ├── duyetnaptien.css          # Top-up approval styles
│   ├── infoad.css                # Ad info styles
│   ├── kdbaidang.css             # Product dashboard styles
│   ├── kdbaidangct.css           # Product dashboard detail styles
│   ├── kdnaptien.css             # Top-up dashboard styles
│   ├── managePost.css            # Post management styles
│   ├── profile.css               # Profile page styles
│   ├── qldoanhthu.css            # Revenue management styles
│   ├── style.css                 # Main stylesheet
│   ├── style.min.css             # Minified main stylesheet
│   └── styles-index.css          # Index page styles
│
├── data/                         # Database files
│   └── choviet29.sql             # Database dump/schema
│
├── helpers/                      # Helper utilities
│   ├── EmailNotification.php     # Email sending helper
│   ├── logger.php                # Logging utility
│   ├── RateLimiter.php           # Rate limiting for API
│   ├── Security.php              # Security functions (CSRF, XSS, SQL injection protection)
│   └── url_helper.php            # URL/routing helpers
│
├── img/                          # Images & assets
│   └── [image files]
│
├── js/                           # JavaScript files
│   ├── chat.js                   # Chat functionality
│   ├── csrf-handler.js           # CSRF token handling
│   ├── dangtin.php               # Post creation (PHP)
│   ├── duyetnaptienscript.js     # Top-up approval script
│   ├── main.js                   # Main JavaScript
│   ├── managePost.php            # Post management (PHP)
│   ├── profile.php               # Profile operations (PHP)
│   ├── server.js                 # Node.js WebSocket server
│   ├── theme-toggle.js           # Theme switching
│   └── toast.js                  # Toast notifications
│
├── lib/                          # Third-party libraries
│   ├── animate/                  # CSS animations
│   ├── easing/                   # Animation easing functions
│   └── owlcarousel/              # Owl carousel library
│
├── loginlogout/                  # Authentication pages
│   ├── login.php                 # Login page
│   ├── signup.php                # Sign up page
│   ├── css/                      # Login page styles
│   ├── fonts/                    # Fonts
│   ├── images/                   # Images
│   ├── js/                       # Scripts
│   └── video/                    # Video assets
│
├── logs/                         # Application logs (runtime)
│
├── model/                        # Data Models (MVC)
│   ├── mC2COrder.php             # C2C order model
│   ├── mCategory.php             # Category model
│   ├── mChat.php                 # Chat model
│   ├── mConnect.php              # Database connection
│   ├── mDetailProduct.php        # Product detail model
│   ├── mInventory.php            # Inventory model
│   ├── mLivestream.php           # Livestream model
│   ├── mOtp.php                  # OTP model
│   ├── mPost.php                 # Post/listing model
│   ├── mProduct.php              # Product model
│   ├── mProfile.php              # Profile model
│   ├── mReview.php               # Review model
│   ├── mTopUp.php                # Top-up model
│   ├── mTransaction.php          # Transaction model
│   ├── mUser.php                 # User model
│   └── [more models...]
│
├── scss/                         # SCSS source files (compiled to CSS)
│   └── [scss files]
│
├── view/                         # View templates
│   └── [HTML template files]
│
├── vendor/                       # Composer dependencies
│   └── [PHP dependencies]
│
├── .git/                         # Git repository
├── .gitignore                    # Git ignore rules
├── .htaccess                     # Apache mod_rewrite rules
├── admin.php                     # Admin panel entry point
├── checkout.php                  # Checkout page
├── composer.json                 # PHP dependencies
├── composer.lock                 # Locked composer versions
├── index.php                     # Main entry point
├── install_composer.bat          # Composer installation script (Windows)
├── install_packages.bat          # Package installation script (Windows)
├── my_orders.php                 # My orders page
├── package.json                  # Node.js dependencies
├── package-lock.json             # Locked npm versions
├── show_packages.php             # Show packages page
├── start_all.bat                 # Start all services script
├── start_unified_server.bat      # Start unified server script
├── test.php                      # Test/debug page
└── node_modules/                 # Node.js dependencies
```

---

## 🔑 Các Tệp Chính

### Entry Points

1. **index.php** - Trang chủ
   - Hiển thị danh mục sản phẩm
   - Danh sách sản phẩm
   - Tìm kiếm, lọc
   - 341 lines

2. **admin.php** - Admin Panel
   - Quản lý sản phẩm
   - Quản lý danh mục
   - Quản lý đơn hàng
   - Xem doanh thu

3. **checkout.php** - Trang thanh toán
   - Giỏ hàng
   - VNPay integration
   - Xác nhận đơn hàng

4. **my_orders.php** - Đơn hàng của tôi
   - Xem đơn hàng
   - Trạng thái vận chuyển
   - Hủy đơn

### Controllers (Business Logic)

**Công Dân (Buyers/Sellers):**
- `cLoginLogout.php` - Xác thực người dùng
- `cProfile.php` - Cài đặt tài khoản
- `cUser.php` - Quản lý thông tin người dùng
- `cProduct.php` - Quản lý sản phẩm
- `cPost.php` - Đăng bài/listing
- `cChat.php` - Nhắn tin với bên kia
- `cReview.php` - Đánh giá sản phẩm

**Quản Trị (Admin):**
- `cQLdanhmuc.php` - Quản lý danh mục
- `cQLdoanhthu.php` - Xem doanh thu
- `cQLgiaodich.php` - Quản lý giao dịch
- `cQLthongtin.php` - Quản lý thông tin hệ thống

**Tính Năng Khác:**
- `cLivestream.php` - Live stream
- `cTopUp.php` - Nạp tiền ví
- `cOtp.php` - OTP verification
- `cC2COrder.php` - C2C trading
- `cReview.php` - Đánh giá

### Models (Data Layer)

- `mConnect.php` - Kết nối MySQL
- `mUser.php` - User queries
- `mProduct.php` - Product queries
- `mChat.php` - Chat queries
- `mLivestream.php` - Livestream queries
- `mTransaction.php` - Transaction queries

---

## 🗄️ Database Schema (choviet29)

### Main Tables

```
users                    # Người dùng
├── id (INT, PK)
├── username (VARCHAR)
├── email (VARCHAR)
├── password (VARCHAR, hashed)
├── avatar (VARCHAR)
├── phone (VARCHAR)
├── address (VARCHAR)
├── balance (DECIMAL)
├── role (ENUM: user, seller, admin)
├── status (ENUM: active, inactive, banned)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

products                 # Sản phẩm
├── id (INT, PK)
├── seller_id (INT, FK -> users)
├── category_id (INT, FK)
├── title (VARCHAR)
├── description (TEXT)
├── price (DECIMAL)
├── image (VARCHAR)
├── status (ENUM: active, sold, hidden)
├── rating (DECIMAL)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

categories               # Danh mục
├── id (INT, PK)
├── name (VARCHAR)
├── description (TEXT)
├── icon (VARCHAR)
└── created_at (TIMESTAMP)

orders                   # Đơn hàng
├── id (INT, PK)
├── buyer_id (INT, FK)
├── seller_id (INT, FK)
├── product_id (INT, FK)
├── quantity (INT)
├── total_price (DECIMAL)
├── status (ENUM: pending, confirmed, shipped, delivered, cancelled)
├── payment_method (VARCHAR)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

chats                    # Tin nhắn
├── id (INT, PK)
├── sender_id (INT, FK)
├── receiver_id (INT, FK)
├── message (TEXT)
├── is_read (BOOLEAN)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

reviews                  # Đánh giá
├── id (INT, PK)
├── product_id (INT, FK)
├── reviewer_id (INT, FK)
├── rating (INT, 1-5)
├── comment (TEXT)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

livestreams              # Live stream
├── id (INT, PK)
├── seller_id (INT, FK)
├── title (VARCHAR)
├── description (TEXT)
├── status (ENUM: scheduled, live, ended)
├── viewers (INT)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

wallets/topups           # Ví/Nạp tiền
├── id (INT, PK)
├── user_id (INT, FK)
├── amount (DECIMAL)
├── method (VARCHAR)
├── status (ENUM: pending, approved, rejected)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

---

## 🔌 APIs

### Chat APIs
- `GET /api/chat-api.php?action=fetchMessages` - Lấy tin nhắn
- `POST /api/chat-api.php` - Gửi tin nhắn
- `GET /api/chat-unread.php?user_id=X` - Đếm tin chưa đọc
- `GET /api/chat-user-info.php?user_id=X` - Lấy info người dùng

### Livestream APIs
- `POST /api/create-livestream.php` - Tạo live stream
- `GET /api/livestream-api.php?action=getStream` - Lấy stream info
- `POST /api/livestream-api.php?action=updateViewers` - Cập nhật viewers

### Review APIs
- `POST /api/review-api.php` - Gửi đánh giá
- `GET /api/check-reviewed.php?product_id=X` - Kiểm tra đã review
- `GET /api/review-api.php?product_id=X` - Lấy reviews của sản phẩm

### Payment APIs
- `POST /api/vnpay/` - VNPay integration endpoints

---

## 🛡️ Security Features

### Implemented in `helpers/Security.php`
1. **CSRF Protection** - CSRF token validation
2. **XSS Prevention** - HTML escaping
3. **SQL Injection Prevention** - Input validation & prepared statements
4. **Session Security** - Secure session handling
5. **Password Hashing** - bcrypt password hashing

### Best Practices
- Input validation trước khi xử lý
- Output escaping trước khi hiển thị
- Prepared statements trong database queries
- HTTPS/SSL requirement
- Rate limiting trên API

---

## 🚀 Key Features

### 1. Product Marketplace
- [x] Browse products by category
- [x] Search & filter
- [x] View product details
- [x] User ratings & reviews
- [x] Seller profiles

### 2. Seller Features
- [x] List products for sale
- [x] Manage inventory
- [x] View orders
- [x] Check sales analytics
- [x] Live stream support
- [x] Wallet balance management

### 3. Buyer Features
- [x] Browse & search products
- [x] Add to cart & checkout
- [x] VNPay payment integration
- [x] Order tracking
- [x] Rate & review products
- [x] Messaging with sellers

### 4. Chat System
- [x] Real-time messaging (WebSocket)
- [x] Message persistence (JSON file)
- [x] Unread message counter
- [x] File sharing in chat

### 5. Admin Panel
- [x] Manage users
- [x] Manage products & categories
- [x] View transactions & revenue
- [x] Approve top-ups
- [x] View reports

### 6. Livestream
- [x] Create live streams
- [x] Live shopping
- [x] Package management

### 7. Payment System
- [x] VNPay integration
- [x] Wallet top-up
- [x] Transaction history

---

## 📦 Dependencies

### PHP (Composer)
```json
{
    "phpmailer/phpmailer": "^6.10"
}
```

### Node.js (npm)
```json
{
    "ws": "^8.18.2"
}
```

---

## 🔧 Development Setup

### Local Environment (Windows XAMPP)

1. **PHP Configuration**
   - Apache modules: rewrite, ssl, headers
   - PHP extensions: mysql, curl, gd, mbstring, zip

2. **Database**
   - Import: `data/choviet29.sql`
   - User: admin / 123456
   - Database: choviet29

3. **Email Configuration**
   - Provider: Gmail SMTP
   - Config file: `config/email_config.php`

4. **WebSocket Server**
   - Start: `npm start` or `node js/server.js`
   - Port: 3000 (local), 8080 (configured)
   - Config: `config/server_config.js`

---

## 📱 Responsive Design

- Bootstrap 4+ grid system
- Mobile-first approach
- CSS media queries
- Responsive images
- Touch-friendly UI

---

## 🎨 Frontend Libraries

- **Bootstrap** - CSS framework
- **Font Awesome** - Icons
- **Owl Carousel** - Image carousel
- **Animate.css** - Animations
- **Custom CSS** - Themed styling

---

## 📊 Performance Optimizations

- Lazy loading images
- CSS/JS minification
- Browser caching headers
- GZIP compression
- Database query optimization
- Connection pooling

---

## 🐛 Common Issues & Solutions

### Database Connection Error
- Check MySQL running: `mysql -u admin -p123456`
- Verify credentials in `model/mConnect.php`
- Check database exists: `SHOW DATABASES;`

### WebSocket Connection Error
- Check Node.js running: `node js/server.js`
- Verify port 3000 is open
- Check firewall rules

### File Upload Error
- Check directory permissions
- Verify upload_max_filesize in php.ini
- Check disk space

### Email Not Sending
- Verify SMTP credentials
- Check port 587 is open
- Enable "Less secure apps" if using Gmail

---

## 📝 Configuration Files Guide

### `config/path_config.php`
- Dynamic path management
- Base URL generation
- Node.js server config

### `config/server_config.js`
- WebSocket server settings
- Project root path
- Chat directory path

### `config/email_config.php`
- SMTP server details
- Email credentials
- Sender information

### `.htaccess`
- URL rewriting rules
- Route configuration
- Static file handling

---

## 🎯 Deployment Checklist

- [ ] Read: `DEPLOYMENT_GUIDE_VI.md`
- [ ] Create DigitalOcean Droplet
- [ ] Install: Apache, PHP, MySQL, Node.js
- [ ] Clone: Repository
- [ ] Configure: Database, Email, Paths
- [ ] Setup: SSL certificate
- [ ] Test: All features
- [ ] Monitor: Logs & performance
- [ ] Backup: Database regularly

---

## 📞 Support

For deployment help, see: **DEPLOYMENT_GUIDE_VI.md**
For code questions, check: Inline comments in source files
GitHub Issues: https://github.com/HoangAn2912/muabandocu/issues

---

## 📄 File Summary Statistics

| Category | Count | Purpose |
|----------|-------|---------|
| Controllers | 20+ | Business logic |
| Models | 15+ | Data operations |
| Views | 30+ | HTML templates |
| CSS Files | 20+ | Styling |
| JS Files | 10+ | Frontend logic |
| API Endpoints | 10+ | REST APIs |
| Config Files | 3+ | Configuration |
| Helpers | 5+ | Utilities |

**Total: 100+ PHP/JS files**

---

**Xem file DEPLOYMENT_GUIDE_VI.md để deploy lên DigitalOcean** 🚀
