<?php
class Connect {
    public function connect() {
        // Thiết lập múi giờ PHP cho toàn ứng dụng
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
        }

        // Read DB config from environment variables with fallbacks to defaults
        $dbHost = getenv('DB_HOST') ?: 'localhost';
        $dbPort = getenv('DB_PORT') ?: '';
        $dbUser = getenv('DB_USER') ?: 'admin';
        $dbPass = getenv('DB_PASS') ?: '123456';
        $dbName = getenv('DB_NAME') ?: 'choviet29';

        // If a port is specified and the host is 'localhost', force TCP by using 127.0.0.1
        // This avoids issues where mysqli attempts a Unix socket that doesn't exist
        $connectHost = $dbHost;
        if ($dbHost === 'localhost' && !empty($dbPort)) {
            $connectHost = '127.0.0.1';
        }

        // Try to connect using mysqli; include port if provided. Catch exceptions to log details.
        try {
            if (!empty($dbPort)) {
                $con = mysqli_connect($connectHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
            } else {
                $con = mysqli_connect($connectHost, $dbUser, $dbPass, $dbName);
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Database Connection Exception: " . $e->getMessage());
            echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
            exit();
        }

        if (!$con) {
            // Use error_log instead of echo in production; keep echo for backward compatibility
            error_log("Database Connection Error: " . mysqli_connect_error());
            echo "Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error();
            exit();
        } else {
            mysqli_query($con, "SET NAMES 'utf8'");
            // Thiết lập múi giờ cho phiên MySQL để NOW()/TIMESTAMP đồng bộ +07:00
            @mysqli_query($con, "SET time_zone = '+07:00'");
            return $con;
        }
    }
}
?>

