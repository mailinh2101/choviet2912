<?php
/**
 * Helper functions for URL generation
 */

/**
 * Lấy base URL động dựa trên thư mục hiện tại
 * @return string Base URL
 */
function getBaseUrl() {
    // 🔍 Phát hiện protocol đúng: kiểm tra X-Forwarded-Proto (từ proxy/load balancer)
    $protocol = 'http';
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        // DigitalOcean App Platform, AWS, Heroku, etc sử dụng header này
        $protocol = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
    } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        // Local/traditional HTTPS
        $protocol = 'https';
    }
    
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $path = dirname($scriptName);
    
    // Sửa lỗi: Nếu đang trong thư mục controller, đi lên 1 cấp
    if (strpos($path, '/controller') !== false) {
        $path = dirname($path);
    }
    
    // Loại bỏ trailing slash nếu không phải root
    if ($path !== '/') {
        $path = rtrim($path, '/');
    }
    
    return $protocol . '://' . $host . $path;
}

/**
 * Lấy base path cho assets (CSS, JS, images)
 * @return string Base path
 */
function getBasePath() {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $path = dirname($scriptName);
    
    // Sửa lỗi: Nếu đang trong thư mục controller, đi lên 1 cấp
    if (strpos($path, '/controller') !== false) {
        $path = dirname($path);
    }
    
    // Loại bỏ trailing slash nếu không phải root
    if ($path !== '/') {
        $path = rtrim($path, '/');
    }
    
    return $path;
}

/**
 * Tạo URL thân thiện cho trang profile
 * @param int $userId ID của người dùng
 * @return string URL thân thiện hoặc fallback URL
 */
function getProfileUrl($userId) {
    require_once __DIR__ . '/../model/mProfile.php';
    $model = new mProfile();
    $user = $model->getUserById($userId);
    
    if (!$user) {
        return 'index.php?thongtin=' . $userId;
    }
    
            return $user['username'];
}

/**
 * Tạo URL thân thiện cho trang profile với slug
 * @param int $userId ID của người dùng
 * @return string URL thân thiện với slug hoặc fallback URL
 */
function getProfileUrlWithSlug($userId) {
    require_once __DIR__ . '/../model/mProfile.php';
    $model = new mProfile();
    $user = $model->getUserById($userId);
    
    if (!$user) {
        return 'index.php?thongtin=' . $userId;
    }
    
            return $model->createSlug($user['username']);
}
?>


