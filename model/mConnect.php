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

        // Use mysqli_init + mysqli_real_connect so we can set a connection timeout and
        // avoid long blocking when the DB is unreachable.
        $mysqli = mysqli_init();
        // Set low connect timeout (seconds)
        if (defined('MYSQLI_OPT_CONNECT_TIMEOUT')) {
            mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        }

        // Optional: set SSL mode if DB_SSLMODE=REQUIRED (handled later if needed)

        $connected = false;
        try {
            if (!empty($dbPort)) {
                $connected = @mysqli_real_connect($mysqli, $connectHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
            } else {
                $connected = @mysqli_real_connect($mysqli, $connectHost, $dbUser, $dbPass, $dbName);
            }
        } catch (Throwable $e) {
            error_log("Database Connection Throwable: " . $e->getMessage());
        }

        if ($connected) {
            $con = $mysqli;
        } else {
            $err = mysqli_connect_error();
            error_log("Database Connection Error: " . $err);
            echo "Lỗi kết nối cơ sở dữ liệu: " . $err;
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

